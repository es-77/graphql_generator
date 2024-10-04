composer require emmanuelsaleem/graphqlgenerator
install package composer require nuwave/lighthouse

http://localhost:8000/graphql

# get query
query {
  Users(first: 31) {
    data {
      id
      name
      email
    }
    paginatorInfo {
      total
      count
      perPage
      currentPage
      lastPage
    }
  }
}
# save query 
# mutation {
#   createUser(
#     id: "12345",
#     name: "John Doe",
#     email: "john@example.com",
#     password: "password123",
#     created_at: "2023-01-01",
#     updated_at: "2023-01-01"
#   ) {
#     id
#     name
#     email
#   }
# }

# update query
# mutation {
#   updateUser(
#     id: "12345",
#     name: "John Doe Updated",
#     email: "john.updated@example.com"
#   ) {
#     id
#     name
#     email
#   }
# }

# delete query 

# mutation {
#   deleteUser(id: "12345") {
#     id
#     name
#   }
# }



