# 第1回デモチェックリスト

## ネットワーク

- `Metasploitable3 Ubuntu` は隔離された `vagrant-libvirt` ネットワークにいる
- `Bridged Adapter` になっていない
- `Metasploitable3 Ubuntu` が研究室LANのIPを持っていない
- `Metasploitable3 Ubuntu` がインターネットへ直接出られない
- 研究室PCからだけ `Metasploitable3 Ubuntu` に到達できる

## 接続

- 会場PCから研究室PCへ SSH できる
- 研究室PCで `nmap` が動く
- 研究室PCで必要ならブラウザまたは確認手段がある

## 対象確認

- デモ対象IPを確認した
- `nmap` の対象を単一IPに固定した
- 広いCIDRや研究室LAN全体を対象にしない

## デモ内容

- 偵察の説明を準備した
- 列挙の説明を準備した
- HTTPサービスの説明を準備した
- `/staff.php` を `gobuster` で見つける手順を準備した
- OSコマンドインジェクションの通常入力と安全な確認入力を準備した
- 防御策の説明を準備した

## やらないこと

- reverse shell
- 権限昇格
- 永続化
- 横展開
- DoS
- 外部通信

## 本番前

- VMスナップショットを取った
- デモ後にロールバックできる
- 録画バックアップがある、または作る予定がある
- 主要コマンドをメモしてある
