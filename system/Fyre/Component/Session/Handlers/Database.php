<?php

namespace Fyre\Component\Session\Handlers;

use 
    SessionHandlerInterface,

    Config\Services,
    Fyre\Component\Session\SessionHandler;

use function
    md5,
    time;

class Database extends SessionHandler implements SessionHandlerInterface
{
    private $db;
    private $table;
    private $sessionId;
    private $lock;
    private $fingerprint;
    private $rowExists = false;

    public function open($save_path, $session_name)
    {
        $this->db = Services::database();

        if ( ! $this->db) {
            Services::logger()->error('Unable to load session database.');
        }

        $this->table = $save_path;

        return !! $this->db;
    }

    public function close()
    {
        return ! $this->lock || $this->releaseLock($this->sessionId);
    }

    public function read($session_id)
    {
		if ( ! $this->getLock($session_id)) {
            $this->_fingerprint = md5('');
            return '';
        }

        $this->sessionId = $session_id;

        $query = $this->db
            ->reset()
            ->table($this->table)
            ->select('data')
            ->where('id', $session_id)
            ->get();

        $result = $query->row();

        $query->free();

        if ( ! $result) {
            $this->rowExists = false;
            $this->fingerprint = md5('');
            return '';
        }

        $this->fingerprint = md5($result->data);
        $this->rowExists = true;
        return $result->data;
    }

    public function write($session_id, $session_data)
    {
		if ($this->sessionId && $this->sessionId !== $session_id) {
            if ( ! $this->releaseLock() || ! $this->getLock($session_id)) {
                return false;
            }

            $this->rowExists = false;
            $this->sessionId = $session_id;
        } else if ( ! $this->lock) {
            return false;
        }

		if ( ! $this->rowExists) {
            $query = $this->db
                ->reset()
                ->table($this->table)
                ->set(
                    [
                        'id' => $session_id,
                        'ipAddress' => $_SERVER['REMOTE_ADDR'],
                        'createdDate' => time(),
                        'data' => $session_data
                    ]
                )
                ->insert();

			if ($query) {
				$this->fingerprint = md5($session_data);
				$this->rowExists = true;
				return true;
            }

			return false;
		}

		$data = [
            'updatedDate' => time()
        ];

		if ($this->fingerprint !== md5($session_data)) {
            $data['data'] = $session_data;
        }

        $query = $this->db
            ->reset()
            ->table($this->table)
            ->set($data)
            ->where('id', $session_id)
            ->update();

		if ($query) {
			$this->fingerprint = md5($session_data);
			return true;
        }

		return false;
    }

    public function destroy($session_id)
    {
		if ($this->lock) {
            $query = $this->db
                ->reset()
                ->table($this->table)
                ->where('id', $session_id)
                ->delete();

			if ( ! $query) {
                return false;
            }
		}

		return $this->close();
    }

    public function gc($lifetime)
    {
        return $this->db
            ->reset()
            ->table($this->table)
            ->groupStart()
                ->where('updatedDate', null)
                ->where('createdDate <', time() - $lifetime)
            ->groupEnd()
            ->orWhere('updatedDate <', time() -> $lifetime)
            ->delete();
    }

    private function getLock($session_id)
    {
        $lock = $this->db->getLock($session_id);

        if ($lock) {
            $this->lock = $lock;
 
			return true;
		}

		return false;
    }

    private function releaseLock($session_id)
    {
        if ( ! $this->lock) {
            return true;
        }

        $unlock = $this->db->releaseLock($session_id);

        if ($unlock) {
            $this->lock = null;

            return true;
        }

        return false;
    }

}
