<?php

require_once('vendor/autoload.php');

use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Address\AddressCreator;
use BitWasp\Bitcoin\Key\Deterministic\HdPrefix\GlobalPrefixConfig;
use BitWasp\Bitcoin\Key\Deterministic\HdPrefix\NetworkConfig;
use BitWasp\Bitcoin\Network\Slip132\BitcoinTestnetRegistry;
use BitWasp\Bitcoin\Key\Deterministic\Slip132\Slip132;
use BitWasp\Bitcoin\Key\KeyToScript\KeyToScriptHelper;
use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Bitcoin\Serializer\Key\HierarchicalKey\Base58ExtendedKeySerializer;
use BitWasp\Bitcoin\Serializer\Key\HierarchicalKey\ExtendedKeySerializer;

class HD {
  
  private $network = NULL;

  public function __construct($network = 'bitcoinTestnet') {
    if (version_compare(PHP_VERSION, '5.3') >= 0) {
      $this->network = NetworkFactory::$network();
    } elseif (version_compare(PHP_VERSION, '5.2.3') >= 0) {
      $this->network = call_user_func("NetworkFactory::$network");
    } else {
      $this->network = call_user_func('NetworkFactory', $network);
    }
  }

  public function set_xpub($xpub) {
    $this->xpub = $xpub;
  }


  public function address_from_master_pub($path = '0/0') {

    $adapter = Bitcoin::getEcAdapter();
    $slip132 = new Slip132(new KeyToScriptHelper($adapter));
    $bitcoin_prefixes = new BitcoinTestnetRegistry();

    if ($this->xpub !== NULL) {
      $pubPrefix = $slip132->p2pkh($bitcoin_prefixes);
      $pub = $this->xpub;
    }

    $config = new GlobalPrefixConfig([
      new NetworkConfig($this->network, [
        $pubPrefix,
      ])
    ]);

    $serializer = new Base58ExtendedKeySerializer(
      new ExtendedKeySerializer($adapter, $config)
    );

    $key = $serializer->parse($this->network, $pub);
    $child_key = $key->derivePath($path);

    return $child_key->getAddress(new AddressCreator())->getAddress($this->network);
  }

}