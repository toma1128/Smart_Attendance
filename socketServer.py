"""
    File : socketServer.py
    Date : 2024/06/25
    Author : H.Kitagawa
"""

# Python同士でのソケット通信
# サーバサイド

import pickle                       # バイト列変換
import socket                       # ソケット通信

# ソケット通信用変数定義
host = "100.118.16.63"                  # ホスト
port = 82                         # ポート番号

# ソケット作成
try:
    # 使用プロトコル指定（IPv4, TCP）
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

    # 使用ホスト、ポート指定
    server.bind((host, port))

    # 接続待機状態へ遷移（同時接続可能数：50）
    server.listen(50)
    print("サーバーがポート82で待機中…")

    # 接続待ち
    while True :
        # 接続確立
        clientSocket, clientAddress = server.accept()
        print("接続成功 : ", clientAddress)

        # データ受け取り（最大1024byte）
        data = clientSocket.recv(1024)
        print("受け取り成功 : ", pickle.loads(data))




except Exception as e:
    # エラー表示
    print("error : \n", e)

finally:
    # ソケット通信終了
    server.close()


