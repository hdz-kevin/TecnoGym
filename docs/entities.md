# Gym Management System

## Entities

---

### User
Represents the system users (admins/staff).

| Field       | Constraints | Description              |
|-------------|-------------|--------------------------|
| id          | PK          | Primary key              |
| email       | UQ          | Unique email address     |
| name        |             | First name               |
| password    |             | Hashed password          |

---

### Member
Represents gym members.

| Field       | Constraints | Description                          |
|-------------|-------------|--------------------------------------|
| id          | PK          | Primary key                          |
| name        |             | Full name                            |
| code        | NULL        | Optional member code                 |
| status      | ENUM        | Member status (e.g. active/inactive) |
| gender      | ENUM        | Gender (e.g. male/female)            |
| birth_date  | NULL        | Date of birth (optional)             |
| photo       | NULL        | Path or URL to profile photo         |

---

### MembershipType
Catalog of membership types available (ej. Estudent, General)

| Field | Constraints | Description     |
|-------|-------------|-----------------|
| id    | PK          | Primary key     |
| name  |             | Type name       |

---

### Membership
Represent a membership of a member

| Field              | Constraints         | Description                         |
|--------------------|---------------------|-------------------------------------|
| id                 | PK                  | Primary key                         |
| member_id          | FK → Member         | The member who owns this membership |
| membership_type_id | FK → MembershipType | The type of membership              |
| status             |                     | Membership status                   |

**Relations:**
- `member_id` → `members.id` (Update: CASCADE, Delete: RESTRICT)
- `membership_type_id` → `membership_types.id` (Update: CASCADE, Delete: RESTRICT)

---

### Duration
Defines the duration options available for a membership type (e.g. 1 month, 3 months). Each duration belongs to a specific membership type and has its own price. The `price_paid` stored in Period preserves the price at the time of payment, so editing a duration's price only affects future periods.

| Field              | Constraints          | Description                         |
|--------------------|----------------------|-------------------------------------|
| id                 | PK                   | Primary key                         |
| membership_type_id | FK → MembershipType  | The membership type this belongs to |
| name               |                      | Duration label (e.g. "1 Month")     |
| amount             |                      | Numeric amount                      |
| unit               |                      | Unit of time (e.g. days, months)    |
| price              |                      | Price for this duration             |

**Relations:**
- `membership_type_id` → `membership_types.id` (Update: CASCADE, Delete: RESTRICT)

---

### Period
Represents a paid period of a membership. Each time a member renews or starts their membership, a new period is created based on a chosen duration. The `price_paid` field stores the actual amount paid at that moment, independent of any future changes to the duration's price.

| Field         | Constraints          | Description                                |
|---------------|----------------------|--------------------------------------------|
| id            | PK                   | Primary key                                |
| membership_id | FK → Membership      | The membership this period belongs to      |
| duration_id   | FK → Duration        | The chosen duration                        |
| start_date    |                      | Period start date                          |
| end_date      |                      | Period end date                            |
| price_paid    |                      | Actual amount paid                         |
| status        |                      | Period status (e.g. in_progress/completed) |

**Relations:**
- `membership_id` → `memberships.id` (Update: CASCADE, Delete: RESTRICT)
- `duration_id` → `durations.id` (Update: CASCADE, Delete: RESTRICT)

---

### Visit
Records a single gym entry made by a **person without an active membership**. Since they are not members, they pay per visit.

| Field    | Constraints | Description            |
|----------|-------------|------------------------|
| id       | PK          | Primary key            |
| visit_at |             | Timestamp of the visit |
| price    |             | Price of the visit     |

---

### Product
Represents a product available for sale in the gym inventory.

| Field       | Constraints | Description                   |
|-------------|-------------|-------------------------------|
| id          | PK          | Primary key                   |
| name        |             | Product name                  |
| price       |             | Current product price         |
| description | NULL        | Optional product description  |
| stock       | NULL        | Current available stock       |
| is_active   |             | Product status (active or not)|

---

### Sale
Represents a sale transaction. A sale can contain multiple products.

| Field   | Constraints | Description                        |
|---------|-------------|------------------------------------|
| id      | PK          | Primary key                        |
| total   |             | Total amount of the sale           |
| sold_at |             | Timestamp of when the sale occurred|

---

### ProductSale
Represents the intermediate entity connecting a Sale and a Product.
It stores the price of the product at the time of the sale.

| Field      | Constraints   | Description                                |
|------------|---------------|--------------------------------------------|
| id         | PK            | Primary key                                |
| sale_id    | FK → Sale     | The sale this line belongs to              |
| product_id | FK → Product  | The product being sold                     |
| quantity   |               | Number of products sold                    |
| unit_price |               | Product price at the time of sale          |
| subtotal   |               | Total for this line (quantity * unit_price)|

**Relations:**
- `sale_id` → `sales.id` (Update: CASCADE, Delete: RESTRICT)
- `product_id` → `products.id` (Update: CASCADE, Delete: RESTRICT)

