#
# Cookbook:: metasploitable
# Attributes:: users
#

default[:users][:zeijyaku] = { username: 'zeijyaku',
                               password: 'zeijyaku',
                               password_hash: '$1$zeijyaku$xFjfI334aFlxBOwUXt5fj0',
                               first_name: 'Zeijyaku',
                               last_name: '',
                               admin: true,
                               salary: '0' }

default[:obsolete_users] = %w(
  leia_organa
  luke_skywalker
  han_solo
  artoo_detoo
  c_three_pio
  ben_kenobi
  darth_vader
  anakin_skywalker
  jarjar_binks
  lando_calrissian
  boba_fett
  jabba_hutt
  greedo
  chewbacca
  kylo_ren
  demo-user
)
