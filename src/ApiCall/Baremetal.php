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

class Baremetal extends AbstractApiCall
{
    /**
     * Reinstalls the bare metal server to a different Vultr one-click application.
     *
     * All data will be permanently lost.
     *
     * @see https://www.vultr.com/api/#baremetal_app_change
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     * @param integer $appId    Application to use. See getAppChangeList().
     *
     * @return integer HTTP response code
     */
    public function appChange($serverId, $appId)
    {
        $args = [
            'SUBID' => (int) $serverId,
            'APPID' => (int) $appId,
        ];

        return $this->adapter->post('baremetal/app_change', $args, true);
    }

    /**
     * Retrieves a list of Vultr one-click applications to which a bare metal server can be changed.
     *
     * Always check against this list before trying to switch applications because it is not possible to switch between every application combination.
     *
     * @see https://www.vultr.com/api/#baremetal_app_change_list
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array
     */
    public function getAppChangeList($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->get('baremetal/app_change_list', $args);
    }

    /**
     * Changes the bare metal server to a different operating system.
     *
     * All data will be permanently lost.
     *
     * @see https://www.vultr.com/api/#baremetal_os_change
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     * @param integer $osId     Operating system to use. See getOsChangeList().
     *
     * @return integer HTTP response code
     */
    public function osChange($serverId, $osId)
    {
        $args = [
            'SUBID' => (int) $serverId,
            'OSID' => (int) $osId,
        ];

        return $this->adapter->post('baremetal/os_change', $args, true);
    }

    /**
     * Retrieves a list of operating systems to which a bare metal server can be changed.
     * Always check against this list before trying to switch operating systems because
     * it is not possible to switch between every operating system combination.
     *
     * @see https://www.vultr.com/api/#baremetal_os_change_list
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array
     */
    public function getOsChangeList($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->get('baremetal/os_change_list', $args);
    }




    /**
     * List all bare metal servers on the current account.
     * This includes both pending and active servers.
     *
     * The "status" field represents the status of the subscription. It will be one of: pending | active | suspended | closed.
     *
     * If you need to filter the list, review the parameters for this function. Currently, only one filter at a time may be applied (SUBID, tag, label, main_ip).
     *
     * @see https://www.vultr.com/api/#baremetal_server_list
     *
     * @param integer $subscriptionId (optional) Unique identifier of a
     * subscription. Only the subscription object will be returned.
     * @param string  $tag            (optional) A tag string. Only subscription
     * objects with this tag will be returned.
     *
     * @param string $label (optional) A text label string. Only subscription objects with this text label will be returned.
     * @param string main_ip (optional) An IPv4 address. Only the subscription matching this IPv4 address will be returned.
     *
     * @return array
     */
    public function getList($subscriptionId = null, $tag = null, $label = null, $main_ip = null)
    {
        $args = [];

        if ($subscriptionId !== null) {
            $args['SUBID'] = $subscriptionId;
        }

        if ($tag !== null) {
            $args['tag'] = $tag;
        }
        
        if ($label !== null ) {
            $args['label'] = $label;
        }
    
        if ($main_ip !== null ) {
            $args['main_ip'] = $main_ip;
        }

        $servers = $this->adapter->get('baremetal/list', $args);

        return $servers;
    }

    /**
     * Wrapper function around getList() to get the details for one server.
     *
     * @param integer $serverId Unique identifier of a subscription. Only the
     * subscription object will be returned.
     *
     * @return array
     */
    public function getDetail($serverId) {
        return $this->getList($serverId);
    }


    /**
     * Wrapper function around getList() to get the details for one server.
     *
     * @param string $tag A tag string. Only subscription objects with this tag
     * will be returned.
     *
     * @return array
     */
    public function getByTag($tag) {
        return $this->getList(null, $tag);
    }
    
    /**
     * Wrapper function around getList() to get the details for one server.
     *
     * @param string $label A labels string. A text label string. Only subscription objects with this text label will be returned.
     *
     * @return array
     */
    public function getByLabel($label) {
        return $this->getList(null, null, $label);
    }
    
    /**
     * Wrapper function around getList() to get the details for one server.
     *
     * @param string $main_ip An IPv4 address. Only the subscription matching this IPv4 address will be returned.
     *
     * @return array
     */
    public function getByMainIp($main_ip) {
        return $this->getList(null, null, null, $main_ip);
    }

    /**
     * Retrieves the (base64 encoded) user-data for this subscription.
     *
     * @see https://www.vultr.com/api/#baremetal_get_user_data
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array
     */
    public function getUserData($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        $userData = $this->adapter->get('baremetal/get_user_data', $args);

        return base64_decode($userData['userdata']);
    }

    /**
     * Sets the cloud-init user-data for this subscription.
     *
     * Note that user-datais not supported on every operating system, and is
     * generally only provided on instance startup.
     *
     * @see https://www.vultr.com/api/#baremetal_set_user_data
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     * @param string  $userData Cloud-init user-data
     *
     * @return integer HTTP response code
     */
    public function setUserData($serverId, $userData)
    {
        $args = [
            'SUBID' => (int) $serverId,
            'userdata' => base64_encode($userData),
        ];

        return $this->adapter->post('baremetal/set_user_data', $args, true);
    }

    

    /**
     * Get the bandwidth used by a bare metal server.
     *
     * @see https://www.vultr.com/api/#baremetal_bandwidth
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array
     */
    public function getBandwidth($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->get('baremetal/bandwidth', $args);
    }

    /**
     * List the IPv4 information of a bare metal server.
     * IP information is only available for bare metal servers in the "active" state.
     *
     * IP information is only available for virtual machines in the "active"
     * state.
     *
     * @see https://www.vultr.com/api/#baremetal_list_ipv4
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array
     */
    public function getIpv4List($serverId)
    {
        $args = ['SUBID' => (int) $serverId];
        $ip = $this->adapter->get('baremetal/list_ipv4', $args);

        return $ip[(int) $serverId];
    }

    

    /**
     * List the IPv6 information of a bare metal server.
     *
     * IP information is only available for virtual machines in the "active"
     * state. If the virtual machine does not have IPv6 enabled, then an empty
     * array is returned.
     *
     * @see https://www.vultr.com/api/#baremetal_list_ipv6
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return array|false False when no IPv6 available
     */
    public function getIpv6List($serverId)
    {
        $args = ['SUBID' => (int) $serverId];
        $ip = $this->adapter->get('baremetal/list_ipv6', $args);

        return !empty($ip) ? $ip[(int) $serverId] : false;
    }

    
    /**
     * Reboot a bare metal server.
     *
     * This is a hard reboot, which means that the server is powered off, then back on.
     *
     * @see https://www.vultr.com/api/#baremetal_reboot
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return integer HTTP response code
     */
    public function reboot($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->post('baremetal/reboot', $args, true);
    }

    
    

    /**
     * Destroy (delete) a bare metal server.
     *
     * All data will be permanently lost, and the IP address will be released.
     * There is no going back from this call.
     *
     * @see https://www.vultr.com/api/#baremetal_destroy
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return integer HTTP response code
     */
    public function destroy($serverId, $getCode = true)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->post('baremetal/destroy', $args, $getCode);
    }

    /**
     * Reinstall the operating system on a bare metal server.
     *
     * All data will be permanently lost, but the IP address will remain the
     * same. There is no going back from this call.
     *
     * @see https://www.vultr.com/api/#baremetal_reinstall
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     *
     * @return integer HTTP response code
     */
    public function reinstall($serverId)
    {
        $args = ['SUBID' => (int) $serverId];

        return $this->adapter->post('baremetal/reinstall', $args, true);
    }

    /**
     * Set the label of a bare metal server.
     *
     * @see https://www.vultr.com/api/#baremetal_label_set
     *
     * @param integer $serverId Unique identifier for this subscription. These
     * can be found using the getList() call.
     * @param string $label     This is a text label that will be shown in the
     * control panel.
     *
     * @return integer HTTP response code
     */
    public function setLabel($serverId, $label)
    {
        $args = [
            'SUBID' => (int) $serverId,
            'label' => $label
        ];

        return $this->adapter->post('baremetal/label_set', $args, true);
    }

    

    /**
     * Create a new bare metal server. You will start being billed for this immediately.
     *
     * The response only contains the SUBID for the new machine.
     *
     * You should use v1/baremetal/list to poll and wait for the machine to be created (as this does not happen instantly).
     *
     * In order to create a server using a snapshot, use OSID 164 and specify a SNAPSHOTID.
     *
     * @see https://www.vultr.com/api/#baremetal_create
     *
     * @param array $config with the following keys:
     *     DCID integer Location to create this virtual machine in.
     *          See region()->getList()
     *     METALPLANID integer Plan to use when creating this server. See v1/plans/list_baremetal. See
     *          See v1/plans/list_baremetal.
     *     OSID integer Operating system to use. See metaData()->getOsList()
     *     ISOID string (optional)  If you've selected the 'custom' operating
     *           system, this is the ID of a specific ISO to mount during the
     *           deployment
     *     SCRIPTID integer (optional) If you've not selected a 'custom'
     *              operating system, this can be the SCRIPTID of a startup
     *              script to execute on boot. See startupscript()->getList()
     *     SNAPSHOTID string (optional) If you've selected the 'snapshot'
     *                operating system, this should be the SNAPSHOTID (see
     *                snapshot->getList()) to restore for the initial
     *                installation
     *     enable_ipv6 string (optional) 'yes' or 'no'.  If yes, an IPv6 subnet
     *                 will be assigned to the machine (where available)
     *     label string (optional) This is a text label that will be shown in
     *           the control panel
     *     SSHKEYID string (optional) List of SSH keys to apply to this server
     *              on install (only valid for Linux/FreeBSD).  See
     *              sshKey()->getList().  Separate keys with commas
     *     APPID integer (optional) If launching an application (OSID 186), this
     *           is the APPID to launch. See metaData()->getAppList().
     *     userdata string (optional) Base64 encoded cloud-init user-data
     *     notify_activate string (optional, default 'yes') 'yes' or 'no'. If
     *                     yes, an activation email will be sent when the server
     *                     is ready.
     *     hostname string (optional) The hostname to assign to this server.
     *     tag string (optional) The tag to assign to this server.
     *
     * @return mixed int|boolean Server ID if creation is successful, false
     * otherwise
     */
    public function create(array $config)
    {
        $regionId = (int) $config['DCID'];
        $planId   = (int) $config['METALPLANID'];

        if (isset($config['userdata'])) {
            // Assume no base64 encoding has been applied when decoding fails!
            if (base64_decode($config['userdata'], true) === FALSE) {
                $config['userdata'] = base64_encode($config['userdata']);
            }
        }

        $this->isAvailable($regionId, $planId);

        $server = $this->adapter->post('baremetal/create', $config);

        return (int) $server['SUBID'];
    }

    /**
     * Determine server availability for a given region and plan.
     *
     * @param integer $regionId Datacenter ID
     * @param integer $planId   VPS Plan ID
     *
     * @return bool
     *
     * @throws ApiException if VPS Plan ID is not available in specified region
     */
    public function isAvailable($regionId, $planId)
    {
        $region = new Region($this->adapter);

        $availability = $region->getAvailability((int) $regionId);
        if (!in_array((int) $planId, $availability)) {
            throw new ApiException(
                sprintf('Plan ID %d is not available in region %d.', $planId, $regionId)
            );
        } else {
            return true;
        }
    }
}
