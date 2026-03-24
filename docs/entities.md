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
| last_name   |             | Last name                |
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

### VisitType
Catalog of visit types used to configure the price of a single gym entry. This entity is **independent of memberships** — it applies only to people who do not have an active membership. The price stored here acts as the default for new visits; editing it does not affect previously registered visits.

| Field | Constraints | Description               |
|-------|-------------|---------------------------|
| id    | PK          | Primary key               |
| name  |             | Visit type name           |
| price |             | Price for this visit type |

---

### Visit
Records a single gym entry made by a person **without an active membership**. Since they are not members, they pay per visit. The `price_paid` field stores the price at the time of the visit, so future changes to the visit type's price do not affect past records.

| Field         | Constraints        | Description                      |
|---------------|--------------------|----------------------------------|
| id            | PK                 | Primary key                      |
| visit_type_id | FK → VisitType     | The type of visit                |
| visit_at      |                    | Timestamp of the visit           |
| price_paid    |                    | Actual amount paid for the visit |

**Relations:**
- `visit_type_id` → `visit_types.id` (Update: CASCADE, Delete: RESTRICT)
