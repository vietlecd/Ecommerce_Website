# Database Migrations

This directory contains database migration files for schema changes and data seeding.

## How to Run Migrations

### Using Docker (Preferred Method)

**For a specific migration:**
```bash
# Copy migration to container first
docker compose cp assets/config/mysql/migrations/001_create_about_qna_tables.sql mysql:/tmp/
# Then run it
docker compose exec mysql bash -c 'mysql -u shoes_user -pshoes_pass shoe < /tmp/001_create_about_qna_tables.sql'
```

**Run all migrations in order:**
```bash
for file in assets/config/mysql/migrations/*.sql; do
  filename=$(basename "$file")
  echo "Running migration: $filename"
  docker compose cp "$file" mysql:/tmp/
  docker compose exec mysql bash -c "mysql -u shoes_user -pshoes_pass shoe < /tmp/$filename"
done
```

### Direct MySQL Connection (When Running MySQL Locally)

```bash
mysql -h127.0.0.1 -P3307 -ushoes_user -pshoes_pass shoe < assets/config/mysql/migrations/001_create_about_qna_tables.sql
```

## Migration Files

- `001_create_about_qna_tables.sql` - Creates About and Q&A tables
- `002_seed_about_qna_data.sql` - Adds initial data to About and Q&A tables

## Notes

- Migrations use `CREATE TABLE IF NOT EXISTS` to prevent errors if tables already exist
- Migration files should be named with sequential numbers (001_, 002_, etc.)
- Schema changes should be in separate files from data seeding for clarity
- Always include rollback instructions in your migrations using `-- @rollback` comments
- After pulling new migrations, run them to keep your local database in sync
