import json
from hdwallet import HDWallet
from hdwallet.cryptocurrencies import BitcoinTestnet
from bit import Key, PrivateKeyTestnet
import mysql.connector


def main():

    #bip44_hdwallet = wallet()

    payments = get_payments()

    if len(payments) == 0:
        print("No payments found")
        exit()

    sum = calc_sum(payments)
    print(f"sum: {sum} BTC")

    if sum == 0:
        print("No balance on addresses")
        exit()

    transfer(payments)

def wallet(index):

    bip44_hdwallet: HDWallet = HDWallet(cryptocurrency=BitcoinTestnet)

    bip44_hdwallet.from_xprivate_key(xprivate_key)

    #print(json.dumps(bip44_hdwallet.dumps(), indent=4, ensure_ascii=False))
    #bip44_hdwallet.from_path("m/44'/1'/0'/0/0")
    bip44_hdwallet.from_path("m/44'/1'/0'/0/"+str(index))
    #bip44_hdwallet.from_path("m/44'/1'/0'/0/0/0/"+str(index))
    return bip44_hdwallet

def transfer(payments):

    for item in payments:

        print("----------------- Payment -----------------")

        index = item[0]

        bip44_hdwallet = wallet(index)

        #print(json.dumps(bip44_hdwallet.dumps(), indent=4, ensure_ascii=False))

        wif = bip44_hdwallet.wif()

        try:

            pr_key = PrivateKeyTestnet(wif)
            print("Address: "+pr_key.address)
            balance = pr_key.get_balance("satoshi")
            print("Balance (satoshi): "+balance)

            if enable_transactions == True:
                if int(balance)>transaction_fee:
                    tx_hash = pr_key.send([(recycling_address, int(balance)-transaction_fee, 'satoshi')], fee=transaction_fee, absolute_fee=True)
                    print("Tx Hash: "+tx_hash)
                else:
                    print("Not enough funds on address")

        except Exception as e:
            print("Error: "+e)


def calc_sum(payments):

    sum = 0

    for item in payments:

        sum+=item[1]

    return sum


def get_payments():

    db = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="unternehmensgruendung"
    )

    cursor = db.cursor()

    cursor.execute("SELECT `id`, `amount` FROM `payments` WHERE `status`=1")

    result = cursor.fetchall()

    cursor.execute("UPDATE `payments` SET `status`=2 WHERE `status`=1")
    db.commit()

    return result


if __name__=="__main__":
    with open('secret_config.json', 'r') as f:
        config = json.load(f)

    xprivate_key = config["root_xprivate_key"]

    recycling_address = "tb1qdffmtr4qmpmf4q4ryxt594ax9nhxgpxft4c8u0"

    enable_transactions = True

    transaction_fee = 1500  # in satoshi

    main()

