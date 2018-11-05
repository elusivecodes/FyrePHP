<?php

namespace Fyre\Database;

trait Transactions
{
    private $transCount = 0;
    private $transStatus;

    public function transBegin()
    {
        $this->transCount++;

        if ($this->transCount > 1) {
            return $this;
        }

        $this->transStatus = true;

        return $this->query('START TRANSACTION') ?
            $this :
            false;
    }

    public function transCommit()
    {
        if ($this->transCount === 0) {
            // nothing to commit
            return $this;
        }

        $this->transCount--;

        if ($this->transCount > 0) {
            $this->transStatus = true;
            return $this;
        }

        $this->transStatus = null;

        return $this->query('COMMIT') ?
            $this :
            false;
    }

    public function transEnd()
    {
        return $this->transStatus() ?
            $this->transCommit() :
            $this->transRollback();
    }

    public function transRollback()
    {
        if ($this->transCount === 0) {
            // nothing to rollback
            return $this;
        }

        $this->transCount--;

        if ($this->transCount > 0) {
            $this->transStatus = false;
            return $this;
        }

        $this->transStatus = null;

        return $this->query('ROLLBACK') ?
            $this :
            false;
    }

    public function transStart()
    {
        return $this->transBegin();
    }

    public function transStatus()
    {
        return $this->transStatus;
    }

}
