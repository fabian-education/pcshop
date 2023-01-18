from hdwallet import HDWallet
from hdwallet.utils import generate_mnemonic, is_mnemonic
from hdwallet.cryptocurrencies import BitcoinTestnet
from typing import Optional
import json
import os

def main():

    LANGUAGE: str = "english"
    # Generate english mnemonic words
    MNEMONIC: str = generate_mnemonic(language="english", strength=256)
    #MNEMONIC = "basket expose teach swamp have retire delay until morning entire wrist hurdle run ride sudden hurt deal antenna flee cream danger state protect surface"
    # Secret passphrase/password for mnemonic
    PASSPHRASE: Optional[str] = None

    assert is_mnemonic(mnemonic=MNEMONIC, language=LANGUAGE)

    bip44_hdwallet: HDWallet = HDWallet(cryptocurrency=BitcoinTestnet)

    bip44_hdwallet.from_mnemonic(
        mnemonic=MNEMONIC, language=LANGUAGE, passphrase=PASSPHRASE
    )

    bip44_hdwallet.from_path("m/44'/1'/0'/0")
    print(json.dumps(bip44_hdwallet.dumps(), indent=4, ensure_ascii=False))

    data = {
        "root_xprivate_key": bip44_hdwallet.root_xprivate_key(),
        "mnemonic": bip44_hdwallet.mnemonic()
    }

    json_object = json.dumps(data, indent=4, ensure_ascii=False)

    if os.path.isfile("secret_config.json") == False:
        with open('secret_config.json', 'w') as f:
            f.write(json_object)
    else:
        print("please remove old secret-config first")
        exit()
    

    data = {
        "pub_key": bip44_hdwallet.xpublic_key() # from 44'/1'/0'/0
    }

    json_object = json.dumps(data, indent=4)

    with open('../wallet/public_config.json', 'w') as f:
        f.write(json_object)



if __name__=="__main__":
    main()

