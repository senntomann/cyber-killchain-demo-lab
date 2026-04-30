# 構築手順案

## 1. 研究室PC側の準備

現在の第1候補は `Metasploitable3` Ubuntu 14.04 版を使う構成である。
構築済み環境の詳細は `docs/metasploitable3_ub1404_build.md` を参照する。

### 必要なもの
- 研究室PC
- 仮想化ソフト
  - VirtualBox
  - VMware
  - KVM/QEMU
- 攻撃ツール
  - `nmap`
  - `metasploit`

### やること
1. 仮想化ソフトを確認する
2. `Metasploitable3` Ubuntu 14.04 版を KVM/libvirt 上に用意する
3. スナップショットを取る

## 2. 脆弱VMのネットワーク設定

### 推奨設定
- `Host-only Adapter`
または
- `Internal Network`

### 確認項目
- 脆弱VMが研究室LANのIPを持っていない
- 脆弱VMがインターネットに出られない
- 研究室PCからは脆弱VMへ到達できる
- 他PCからは脆弱VMが見えない

## 3. 会場PCからの接続

### 方針
- 会場PCから研究室PCへ SSH 接続する
- `nmap` や `metasploit` は研究室PC上で実行する

### 確認項目
- 会場PCから研究室PCへ SSH できる
- 研究室PC上で必要なコマンドが動く
- 発表会場のネットワーク条件で SSH が使える

## 4. デモ前リハーサル

1. 研究室PC上で脆弱VMを起動する
2. 脆弱VMのIPを確認する
3. `nmap` の対象を `Metasploitable3` の単一IPに固定して実行する
4. 必要なら `metasploit` の対象IPも単一IPで確認する
5. デモ後にロールバックできるか確認する

## 5. 本番運用

### 本番の流れ
1. 会場PCから研究室PCへ SSH 接続
2. 研究室PCで `Metasploitable3` 起動確認
3. `nmap` で列挙デモ
4. HTTP サービスを入口に脆弱性のイメージを説明
5. どこで防げたかを説明

### バックアップ
- 録画デモを用意する
- 主要コマンドを事前にメモしておく
- 失敗時にスナップショットへ戻せるようにする

## 6. 今後決めること

- `Metasploitable3` 内で第1回に使うサービスと画面
- 使う仮想化ソフト
- デモで扱うコマンド範囲
- スライドの構成
