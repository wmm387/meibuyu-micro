<?php

namespace Meibuyu\Micro\Model;

use Hyperf\Utils\Context;
use Meibuyu\Micro\Exceptions\ObjectNotExistException;

class Auth
{
    /**
     * @return bool|mixed|string|null
     * @throws ObjectNotExistException
     */
    private static function init()
    {
        if (Context::has('auth'))  {
            return Context::get('auth');
        } else {
            $token = token();
            if (!$token) throw new ObjectNotExistException('Token');
            $auth = redis()->get($token);
            if ($auth) {
                $auth = json_decode($auth, true);
                Context::set('auth', $auth);
                return $auth;
            } else {
                throw new ObjectNotExistException('User');
            }
        }
    }

    /**
     * @return object
     * @throws ObjectNotExistException
     */
    public static function user()
    {
        return self::init();
    }

    /**
     * @return integer
     * @throws ObjectNotExistException
     */
    public static function id()
    {
        return self::init()['id'];
    }

}
