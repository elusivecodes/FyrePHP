<?php

namespace Fyre\Component\Session;

interface SessionInterface
{

    public function destroy(): bool;
    public function destroyCookie(): bool;

}
