<?php

/**
 * Vultr.com Curl Adapter
 *
 * NOTE: part of this code was extracted from
 * https://github.com/usefulz/vultr-api-client, updated for PSR compliance and
 * extended with new API calls.
 *
 * @package Vultr
 * @version 1.0
 * @author  https://github.com/malc0mn - https://github.com/usefulz
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @see     https://github.com/malc0mn/vultr-api-client
 */

namespace Vultr\ApiCall;

use Vultr\Exception\ApiException;

class SshKey extends AbstractApiCall
{
    /**
     * List all the SSH keys on the current account.
     *
     * @see https://www.vultr.com/api/#sshkey_sshkey_list
     *
     * @return array|boolean False when no keys were found
     */
    public function getList()
    {
        $result = $this->adapter->get('sshkey/list');
        if (!count($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Create a new SSH Key.
     *
     * @see https://www.vultr.com/api/#sshkey_create
     *
     * @param string $name Name of the SSH key
     * @param string $key  SSH public key (in authorized_keys format)
     *
     * @return string SSH key ID
     */
    public function create($name, $sshKey)
    {
        $args = [
            'name'     => $name,
            'ssh_key'  => $sshKey,
        ];

        $key = $this->adapter->post('sshkey/create', $args);

        return $key['SSHKEYID'];
    }

    /**
     * Update an existing SSH Key.
     *
     * Note that this will only update newly installed machines. The key will
     * not be updated on any existing machines.
     *
     * @see https://www.vultr.com/api/#sshkey_update
     *
     * @param string $keyId  SSHKEYID of key to update
     * @param string $name   (optional) New name for the SSH key
     * @param string $sshKey (optional) New SSH key contents
     *
     * @return integer HTTP response code
     *
     * @throws ApiException
     */
    public function update($keyId, $name = null, $sshKey = null)
    {
        if ($name === null && $sshKey === null) {
            throw new ApiException(
                sprintf('Please provide name or key to update for key ID %s!', $keyId)
            );
        }

        $args = ['SSHKEYID' => $keyId];

        if ($name !== null) {
            $args['name'] = $name;
        }

        if ($sshKey !== null) {
            $args['ssh_key'] = $sshKey;
        }

        return $this->adapter->post('sshkey/update', $args, true);
    }

    /**
     * Remove a SSH key.
     *
     * Note that this will not remove the key from any machines that already
     * have it.
     *
     * @see https://www.vultr.com/api/#sshkey_destroy
     *
     * @param string $keyId Unique identifier for this SSH key. These can be
     * found using the getList() call.
     *
     * @return integer HTTP response code
     */
    public function destroy($keyId)
    {
        $args = ['SSHKEYID' => $keyId];

        return $this->adapter->post('sshkey/destroy', $args, true);
    }
}
