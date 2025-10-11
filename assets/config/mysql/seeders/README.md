# Database Seeders

This directory contains database seeder files for initial data.

## How to Run Seeders

### Using Docker

Run a specific seeder:
```bash
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/seeders/001_seed_about_qna_data.sql
```

Run all seeders in order:
```bash
for file in assets/config/mysql/seeders/*.sql; do
  docker-compose exec -T mysql mysql -u shoes_user -pshoes_pass shoe < "$file"
done
```

### Direct MySQL Connection

```bash
mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/seeders/001_seed_about_qna_data.sql
```

## Seeder Files

- `001_seed_about_qna_data.sql` - Initial data for About page and Q&A items

## Notes

- Run seeders AFTER running the corresponding migrations
- Seeders insert default/sample data for testing and initial setup
- Run seeders in numerical order
