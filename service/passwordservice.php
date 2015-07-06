<?php
namespace OCA\Passwords\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\Passwords\Db\Password;
use OCA\Passwords\Db\PasswordMapper;


class PasswordService {

    private $mapper;

    public function __construct(PasswordMapper $mapper){
        $this->mapper = $mapper;
    }

    public function findAll($userId) {

        $result = $this->mapper->findAll($userId);
        $arr = json_decode(json_encode($result), true);

        $userKey = $userId;
        $serverKey = \OC_Config::getValue('passwordsalt', '');
        
        foreach($arr as $row => $value)
        {
            $userSuppliedKey = $arr[$row]['website'];
            $encryptedData = $arr[$row]['pass'];
            $e2 = new Encryption(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
            $key = Encryption::makeKey($userKey, $serverKey, $userSuppliedKey);
            
            $arr[$row]['pass'] = $e2->decrypt($encryptedData, $key);

        }

        return $arr;

    }

    private function handleException ($e) {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new NotFoundException($e->getMessage());
        } else {
            throw $e;
        }
    }

    public function find($id, $userId) {
        try {
            return $this->mapper->find($id, $userId);

        // in order to be able to plug in different storage backends like files
        // for instance it is a good idea to turn storage related exceptions
        // into service related exceptions so controllers and service users
        // have to deal with only one type of exception
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function create($loginname, $website, $pass, $userId) {

        $userKey = $userId;
        $serverKey = \OC_Config::getValue('passwordsalt', '');
        $userSuppliedKey = $website;
        $key = Encryption::makeKey($userKey, $serverKey, $userSuppliedKey);
        $e = new Encryption(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $encryptedData = $e->encrypt($pass, $key);

        $password = new Password();
        $password->setLoginname($loginname);
        $password->setWebsite($website);
        $password->setPass($encryptedData);
        //$password->setPass($pass);
        $password->setUserId($userId);
        $password->setCreationDate(date("Y-m-d"));
        return $this->mapper->insert($password);
    }

    public function update($id, $loginname, $website, $pass, $userId) {
        try {
            $password = $this->mapper->find($id, $userId);
            $password->setLoginname($loginname);
            $password->setWebsite($website);
            $password->setPass($pass);
            $password->setCreationDate(date("Y-m-d"));
            return $this->mapper->update($password);
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete($id, $userId) {
        try {
            $password = $this->mapper->find($id, $userId);
            $this->mapper->delete($password);
            return $password;
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

}

// http://stackoverflow.com/questions/5089841/two-way-encryption-i-need-to-store-passwords-that-can-be-retrieved?answertab=votes#tab-top

    /**
     * A class to handle secure encryption and decryption of arbitrary data
     *
     *  Note that this is not just straight encryption. It also has a few other
     *  features in it to make the encrypted data far more secure.  Note that any
     *  other implementations used to decrypt data will have to do the same exact
     *  operations.  
     *
     * Security Benefits:
     *
     * - Uses Key stretching
     * - Hides the Initialization Vector
     * - Does HMAC verification of source data
     *
     */
    class Encryption {

        public static function makeKey($userKey, $serverKey, $userSuppliedKey) {
            $key = hash_hmac('sha512', $userKey, $serverKey);
            $key = hash_hmac('sha512', $key, $userSuppliedKey);
            return $key;
        } 

        /**
         * @var string $cipher The mcrypt cipher to use for this instance
         */
        protected $cipher = '';
        
        /**
         * @var int $mode The mcrypt cipher mode to use
         */
        protected $mode = '';

        /**
         * @var int $rounds The number of rounds to feed into PBKDF2 for key generation
         */
        protected $rounds = 100;

        /**
         * Constructor!
         *
         * @param string $cipher The MCRYPT_* cypher to use for this instance
         * @param int    $mode   The MCRYPT_MODE_* mode to use for this instance
         * @param int    $rounds The number of PBKDF2 rounds to do on the key
         */
        public function __construct($cipher, $mode, $rounds = 100) {
            $this->cipher = $cipher;
            $this->mode = $mode;
            $this->rounds = (int) $rounds;
        }

        /**
         * Decrypt the data with the provided key
         *
         * @param string $data The encrypted datat to decrypt
         * @param string $key  The key to use for decryption
         * 
         * @returns string|false The returned string if decryption is successful
         *                           false if it is not
         */
        public function decrypt($data_hex, $key) {

            if ( !function_exists( 'hex2bin' ) ) {
                function hex2bin( $str ) {
                    $sbin = "";
                    $len = strlen( $str );
                    for ( $i = 0; $i < $len; $i += 2 ) {
                        $sbin .= pack( "H*", substr( $str, $i, 2 ) );
                    }

                    return $sbin;
                }
            }

            $data = hex2bin($data_hex);

            $salt = substr($data, 0, 128);
            $enc = substr($data, 128, -64);
            $mac = substr($data, -64);

            list ($cipherKey, $macKey, $iv) = $this->getKeys($salt, $key);

            //if (!hash_equals(hash_hmac('sha512', $enc, $macKey, true), $mac)) {
            if (!Encryption::hash_equals(hash_hmac('sha512', $enc, $macKey, true), $mac)) {
                 return false;
            }

            $dec = mcrypt_decrypt($this->cipher, $cipherKey, $enc, $this->mode, $iv);

            $data = $this->unpad($dec);

            return $data;
        }

        /**
         * Encrypt the supplied data using the supplied key
         * 
         * @param string $data The data to encrypt
         * @param string $key  The key to encrypt with
         *
         * @returns string The encrypted data
         */
        public function encrypt($data, $key) {
            $salt = mcrypt_create_iv(128, MCRYPT_DEV_URANDOM);
            //list ($cipherKey, $macKey, $iv) = $this->getKeys($salt, $key);
            list ($cipherKey, $macKey, $iv) = Encryption::getKeys($salt, $key);
            //$data = $this->pad($data);
            $data = Encryption::pad($data);
            $enc = mcrypt_encrypt($this->cipher, $cipherKey, $data, $this->mode, $iv);

            $mac = hash_hmac('sha512', $enc, $macKey, true);

            $data = $salt . $enc . $mac;

            $data = bin2hex($salt . $enc . $mac);
            //$data = pack("H*" , $data);
            return $data;
            
            return $salt . $enc . $mac;

        }

        /**
         * Generates a set of keys given a random salt and a master key
         *
         * @param string $salt A random string to change the keys each encryption
         * @param string $key  The supplied key to encrypt with
         *
         * @returns array An array of keys (a cipher key, a mac key, and a IV)
         */
        protected function getKeys($salt, $key) {
            $ivSize = mcrypt_get_iv_size($this->cipher, $this->mode);
            $keySize = mcrypt_get_key_size($this->cipher, $this->mode);
            $length = 2 * $keySize + $ivSize;

            //$key = $this->pbkdf2('sha512', $key, $salt, $this->rounds, $length);
            $key = Encryption::pbkdf2('sha512', $key, $salt, $this->rounds, $length);

            $cipherKey = substr($key, 0, $keySize);
            $macKey = substr($key, $keySize, $keySize);
            $iv = substr($key, 2 * $keySize);
            return array($cipherKey, $macKey, $iv);
        }

        function hash_equals($a, $b) {
            $key = mcrypt_create_iv(128, MCRYPT_DEV_URANDOM);
            return hash_hmac('sha512', $a, $key) === hash_hmac('sha512', $b, $key);
        }

        /**
         * Stretch the key using the PBKDF2 algorithm
         *
         * @see http://en.wikipedia.org/wiki/PBKDF2
         *
         * @param string $algo   The algorithm to use
         * @param string $key    The key to stretch
         * @param string $salt   A random salt
         * @param int    $rounds The number of rounds to derive
         * @param int    $length The length of the output key
         *
         * @returns string The derived key.
         */
        protected function pbkdf2($algo, $key, $salt, $rounds, $length) {
            $size   = strlen(hash($algo, '', true));
            $len    = ceil($length / $size);
            $result = '';
            for ($i = 1; $i <= $len; $i++) {
                $tmp = hash_hmac($algo, $salt . pack('N', $i), $key, true);
                $res = $tmp;
                for ($j = 1; $j < $rounds; $j++) {
                     $tmp  = hash_hmac($algo, $tmp, $key, true);
                     $res ^= $tmp;
                }
                $result .= $res;
            }
            return substr($result, 0, $length);
        }

        protected function pad($data) {
            $length = mcrypt_get_block_size($this->cipher, $this->mode);
            $padAmount = $length - strlen($data) % $length;
            if ($padAmount == 0) {
                $padAmount = $length;
            }
            return $data . str_repeat(chr($padAmount), $padAmount);
        }

        protected function unpad($data) {
            $length = mcrypt_get_block_size($this->cipher, $this->mode);
            $last = ord($data[strlen($data) - 1]);
            if ($last > $length) return false;
            if (substr($data, -1 * $last) !== str_repeat(chr($last), $last)) {
                return false;
            }
            return substr($data, 0, -1 * $last);
        }
    }
