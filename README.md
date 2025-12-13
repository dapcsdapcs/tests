# Mini Online Shop (PHP) — MySQL Entegrasyonu

Kısa demo uygulama: `signup.php`, `login.php`, `index.php`.

Önemli dosyalar:
- `config.php` — `DATABASE_URL` kullanarak PDO bağlantısı oluşturur.
- `db_init.php` — Çalıştırıldığında `users` tablosunu oluşturur.

Hızlı çalışma (geliştirme):

1) İsteğe bağlı: `DATABASE_URL` ortam değişkeni ayarlayın. Örnek format:

```
mysql://user:pass@host:port/dbname?ssl-mode=REQUIRED
```

2) Veritabanı tablosunu oluşturun:

```bash
php db_init.php
```

3) Uygulamayı çalıştırın:

```bash
php -S localhost:8000
```

4) Tarayıcıda açın: http://localhost:8000

Notlar:
- `signup.php` artık `address` alanını alır ve kullanıcıyı MySQL `users` tablosuna kaydeder.
- `login.php` veritabanından doğrulama yapar.

DB SSL ve bağlantı notları:
- Eğer bağlantı hatası alıyorsanız önce PHP uzantılarının yüklü olduğundan emin olun:

```bash
php -m | grep -E "pdo|pdo_mysql|openssl"
```

- Uzaktan MySQL sunucuları genellikle TLS (SSL) isteyebilir. `config.php` URL'deki `ssl-mode` parametresini okur ve varsa sistemdeki yaygın CA bundle yolunu kullanmaya çalışır. Eğer TLS hatası alıyorsanız CA bundle yolunu belirtin veya sunucu yöneticisinden CA dosyasını alın.
- Geliştirme sırasında, bağlantı testini şu komutla yapabilirsiniz:

- Proje ile birlikte bir CA sertifikası sağladıysanız onu `certs/ca.pem` olarak kaydettim; `config.php` önce bu dosyayı kullanmayı dener.
- Eğer kendiniz bir CA dosyası eklemek isterseniz, dosyayı `certs/ca.pem` yoluna koyun ve uygun erişim izinlerini verin (ör. `chmod 644 certs/ca.pem`).

Geliştirme sırasında, bağlantı testini şu komutla yapabilirsiniz:

```bash
php -r "require 'config.php'; echo 'OK';"
```

Public MySQL istemcisi ile bağlanma (örnek):

```bash
mysql --user=upadmin --password=AVNS_i6tNjDLSLM6RPUXvlTP \
	--host=public-test-dgsyoehcssyt.db.upclouddatabases.com --port=11569 defaultdb \
	--ssl-ca=certs/ca.pem --ssl-mode=REQUIRED
```

Not: Parolayı komutta doğrudan yazmak güvenlik riski oluşturur; mümkünse `--password` parametresini çıkartıp istemci sizden şifreyi sormanızı sağlayın.

# fjhsdgfjhksdf