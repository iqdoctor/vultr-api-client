<?php

/**
 * Vultr.com PHP API Client
 *
 * NOTE: this client was forked from
 * https://github.com/usefulz/vultr-api-client and overhauled extensively to be
 * more open for extension and easily installable using our beloved composer.
 *
 * @package Vultr
 * @version 1.0
 * @author  https://github.com/malc0mn - https://github.com/usefulz
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @see     https://github.com/malc0mn/vultr-api-client
 */

namespace Vultr;

use Vultr\Adapter\AdapterInterface;
use Vultr\ApiCall\Baremetal;
use Vultr\ApiCall\BlockStorage;
use Vultr\ApiCall\Dns;
use Vultr\ApiCall\Firewall;
use Vultr\ApiCall\Iso;
use Vultr\ApiCall\MetaData;
use Vultr\ApiCall\Region;
use Vultr\ApiCall\ReservedIp;
use Vultr\ApiCall\Server;
use Vultr\ApiCall\Snapshot;
use Vultr\ApiCall\SshKey;
use Vultr\ApiCall\StartupScript;
use Vultr\ApiCall\User;

class VultrClient
{
    const ENDPOINT = 'https://api.vultr.com/v1/';
    const VERSION  = '1.0';
    const AGENT    = 'Vultr.com PHP API Client';

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Provides dns related calls.
     *
     * @return DNS
     */
    public function dns()
    {
        return new Dns($this->adapter);
    }

    /**
     * Provides block storage related calls.
     *
     * @return BlockStorage
     */
    public function blockStorage()
    {
        return new BlockStorage($this->adapter);
    }

    /**
     * Provides firewall related calls.
     *
     * @return Firewall
     */
    public function firewall()
    {
        return new Firewall($this->adapter);
    }

    /**
     * Provides ISO related calls.
     *
     * @return ISO
     */
    public function iso()
    {
        return new Iso($this->adapter);
    }

    /**
     * Provides account and other related calls.
     *
     * @return MetaData
     */
    public function metaData()
    {
        return new MetaData($this->adapter);
    }

    /**
     * Provides region related calls.
     *
     * @return Region
     */
    public function region()
    {
        return new Region($this->adapter);
    }

    /**
     * Provides reservedip related calls.
     *
     * @return ReservedIp
     */
    public function reservedIp()
    {
        return new ReservedIp($this->adapter);
    }

    /**
     * Provides server script related calls.
     *
     * @return Server
     */
    public function server()
    {
        return new Server($this->adapter);
    }
    
    /**
     * Provides bare metal script related calls.
     *
     * @return Baremetal
     */
    public function baremetal()
    {
        return new Baremetal($this->adapter);
    }

    /**
     * Provides snapshot related calls.
     *
     * @return Snapshot
     */
    public function snapshot()
    {
        return new Snapshot($this->adapter);
    }

    /**
     * Provides SSH key related calls.
     *
     * @return SshKey
     */
    public function sshKey()
    {
        return new SshKey($this->adapter);
    }

    /**
     * Provides startup script related calls.
     *
     * @return StartupScript
     */
    public function startupScript()
    {
        return new StartupScript($this->adapter);
    }

    /**
     * Provides user related calls.
     *
     * @return User
     */
    public function user()
    {
        return new User($this->adapter);
    }
}
