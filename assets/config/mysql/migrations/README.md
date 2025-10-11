# Database Migrations

This directory contains database migration files for schema changes.

## How to Run Migrations

### Using Docker

Run a specific migration:
```bash
docker-compose exec mysql mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/migrations/001_create_about_qna_tables.sql
```

Run all migrations in order:
```bash
for file in assets/config/mysql/migrations/*.sql; do
  docker-compose exec -T mysql mysql -u shoes_user -pshoes_pass shoe < "$file"
done
```

### Direct MySQL Connection

```bash
mysql -u shoes_user -pshoes_pass shoe < assets/config/mysql/migrations/001_create_about_qna_tables.sql
```

## Migration Files

- `001_create_about_qna_tables.sql` - Creates About and Q&A tables for Issue #2

## Notes

- Migrations use `CREATE TABLE IF NOT EXISTS` to prevent errors if tables already exist
- Run migrations in numerical order
- After running migrations, run the corresponding seeders to populate initial data
