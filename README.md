
# Generate Graph Ql 
is a package that create graph ql code for you like get all record with pagnation get record by id create update and delete record all code generate with this amzing package



#  package is
composer require emmanuelsaleem/graphqlgenerator


## next
install this package composer require nuwave/lighthouse


## API Reference

### Route 
http://localhost:8000/graphql

### Get all users



| Query  | Type    | Description                                          |
|--------|---------|------------------------------------------------------|
| `query { Users(first: 31) { data { id name email } paginatorInfo { total count perPage currentPage lastPage } } }` | string  | Fetch a list of users with pagination info            |

---

### Get user by ID

| Query  | Type    | Description                        |
|--------|---------|------------------------------------|
| `query { User(id: "1234") { id name email } }` | string  | Fetch a user by their unique ID    |

---

### Create a new user

| Mutation  | Type    | Description                     |
|-----------|---------|----------------------------------|
| `mutation { createUser(id: "12345", name: "John Doe", email: "john@example.com", password: "password123", created_at: "2023-01-01", updated_at: "2023-01-01") { id name email } }` | string  | Create a new user with given details |

---

### Update an existing user

| Mutation  | Type    | Description                      |
|-----------|---------|----------------------------------|
| `mutation { updateUser(id: "12345", name: "John Doe Updated", email: "john.updated@example.com") { id name email } }` | string  | Update an existing user’s details |

---

### Delete a user

| Mutation  | Type    | Description           |
|-----------|---------|----------------------|
| `mutation { deleteUser(id: "12345") { id name } }` | string  | Delete a user by ID    |

# Contact With me
https://pk.linkedin.com/in/es77




## After installation run command into termainal

php artisan generate:graph-ql-query

