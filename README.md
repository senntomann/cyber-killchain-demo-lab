# Cyber Kill Chain Education Lab

セキュリティ初学者が、攻撃の流れと防御観点を安全に学べるように設計した教育用デモ環境です。  
隔離された仮想マシン上に意図的に脆弱なWebアプリケーションを配置し、`nmap` による偵察、`gobuster` による列挙、OSコマンドインジェクション、内部探索、権限昇格につながる設定ミスの発見までを、サイバーキルチェーンに沿って体験できる構成にしています。

このリポジトリでは、Metasploitable3をベースにしつつ、デモ用に独自実装したWebアプリ、Vagrant/Chefによる差分IaC、攻撃手順書、教育用スライドを公開しています。

## Live Materials

- [プレゼンテーションスライド](https://senntomann.github.io/cyber-killchain-demo-lab/)
- [デモ手順書](./demos/01_attack_flow_metasploitable/README.md)
- [脆弱Webアプリケーション](./src/web_app/)
- [VM構築・カスタマイズ用IaC](./infrastructure/)

## What I Built

- **教育用の脆弱Webアプリケーション**  
  職員向けログイン画面を模したPHPアプリを作成し、入力値をOSコマンドへ直接連結することで、OSコマンドインジェクションを再現できるようにしました。

- **隔離されたデモ環境**  
  Vagrant、libvirt/KVM、Chefを用いて、ホストOSや外部ネットワークから切り離した検証用VM環境を構築しました。

- **デモに必要な攻撃面の整理**  
  不要なサービスを削減し、HTTP、SSH、Microsoft-DSなどデモに必要な要素へ絞ることで、初学者が観察すべきポイントを明確にしました。

- **権限昇格につながる設定ミスの再現**  
  `www-data` から書き換え可能なcron実行スクリプトを配置し、侵入後の内部探索で危険な権限設定を発見する流れを教材化しました。

- **教育用ドキュメントとスライド**  
  攻撃手順だけでなく、「なぜ危険なのか」「どのように防げるのか」を説明するための手順書とスライドを整備しました。

## Demo Scenario

このデモは、単発の脆弱性検証ではなく、攻撃者が段階的に情報を集めて侵入範囲を広げる流れを学ぶことを目的にしています。

1. **Reconnaissance**  
   `nmap` を用いて、対象VMで公開されているポートとサービスを確認します。

2. **Enumeration**  
   `gobuster` を用いて、トップページからリンクされていない職員向けページを発見します。

3. **Exploitation**  
   PHPアプリの入力値がOSコマンドへ直接連結されていることを利用し、OSコマンドインジェクションを実行します。

4. **Command Execution / C2 Concept**  
   Webサーバ権限でコマンドが実行できることを確認し、リバースシェルの考え方を説明します。

5. **Internal Discovery and Privilege Escalation Risk**  
   `www-data` 権限で内部ファイルを確認し、rootが定期実行するcronスクリプトの権限設定ミスを発見します。

## Repository Structure

```text
.
├── docs/                  # 構成メモ、安全ポリシー、スライドアウトライン
├── demos/                 # デモ手順書とgobuster用ワードリスト
├── infrastructure/        # VagrantfileとChefレシピの独自差分
├── slides/                # Slidevによるプレゼンテーション資料
├── src/web_app/           # OSコマンドインジェクション用PHPアプリ
└── .github/workflows/     # GitHub Pagesへのスライド自動デプロイ
```

## Tech Stack

- **Infrastructure**: Vagrant, libvirt/KVM, Chef
- **Base VM**: Metasploitable3 Ubuntu 14.04
- **Web**: Apache, PHP
- **Security Tools**: nmap, gobuster, curl, netcat
- **Documentation / Publishing**: Markdown, Slidev, GitHub Actions, GitHub Pages

## Notes on Metasploitable3

本環境は、Rapid7社が提供する教育用VMである Metasploitable3 Ubuntu 14.04 版をベースにしています。

このリポジトリには、Metasploitable3本体の巨大なソースコード全体は含めていません。代わりに、本デモシナリオのために独自に追加・修正した以下の差分を公開しています。

- Vagrantfileのカスタマイズ
- Chefレシピによるユーザー整理、不要サービス削減、cron設定ミスの配置
- OSコマンドインジェクションを含むPHPアプリケーション
- 攻撃手順書、ワードリスト、スライド資料

実際に同等の環境を構築する場合は、公式Metasploitable3の構成に対して、本リポジトリの差分コードを適用する想定です。

## Safety Policy

このプロジェクトは、許可された隔離環境でのセキュリティ学習のみを目的としています。

- デモ対象はローカルの隔離VMに限定します。
- 大学、企業、研究室などの実ネットワーク全体をスキャン対象にしません。
- 攻撃手順は、管理者の許可がある環境でのみ実行することを前提にしています。
- 脆弱なコードは教育目的で意図的に含めています。本番環境では絶対に使用しないでください。

## Disclaimer

本リポジトリに含まれるコード、設定、手順、資料は、サイバーセキュリティの学習・教育を目的としたものです。許可されていないシステムやネットワークに対して、ここで扱う手法を使用することは法律で禁じられています。
