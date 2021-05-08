package user

import (
	"math/rand"
	"testing"

	"github.com/kkamara/go-ecommerce/models/helper/password"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func TestIsAcceptedRole(t *testing.T) {
	var (
		acceptedRole bool
		expected     = true
	)
	acceptedRole = IsAcceptedRole("")
	expected = true
	if acceptedRole != expected {
		t.Errorf(
			"expected: %v, received %v",
			expected,
			acceptedRole,
		)
	}

	acceptedRole = IsAcceptedRole("vendor")
	if acceptedRole != expected {
		t.Errorf(
			"expected: %v, received %v",
			expected,
			acceptedRole,
		)
	}

	acceptedRole = IsAcceptedRole("moderator")
	if acceptedRole != expected {
		t.Errorf(
			"expected: %v, received %v",
			expected,
			acceptedRole,
		)
	}
}

func TestCreateUser(t *testing.T) {
	timeBeforeCreated := time.Now()
	pwd, err := password.HashPassword("secret")
	if err != nil {
		return
	}
	role := schemas.AcceptedUserRoles[rand.Intn(len(schemas.AcceptedUserRoles))]
	name := faker.Name()
	u := &schemas.User{
		FirstName: name.FirstName(),
		LastName:  name.LastName(),
		Email:     faker.Internet().SafeEmail(),
		Password:  pwd,
		Role:      role,
	}
	user, err := Create(u)
	if err != nil {
		t.Error(err)
	}
	if user.FirstName != u.FirstName {
		t.Errorf("FirstName %s does not match %s", user.FirstName, u.FirstName)
	}
	if user.LastName != u.LastName {
		t.Errorf("LastName %s does not match %s", user.LastName, u.LastName)
	}
	if user.Email != u.Email {
		t.Errorf("Email %s does not match %s", user.Email, u.Email)
	}
	if password.VerifyPassword(user.Password, "secret") != true {
		t.Error("Password does not match secret")
	}
	if user.Role != u.Role {
		t.Errorf("Role %s does not match %s", user.Role, u.Role)
	}
	if !(user.CreatedAt >= timeBeforeCreated) {
		t.Errorf("CreatedAt %s should be greater than or equal to %s", user.CreatedAt, timeBeforeCreated)
	}
	if !(user.UpdatedAt >= timeBeforeCreated) {
		t.Errorf("UpdatedAt %s should be greater than or equal to %s", user.UpdatedAt, timeBeforeCreated)
	}
}

func TestGetAllUsers(t *testing.T) {
	pwd, err := password.HashPassword("secret")
	if err != nil {
		t.Error(err)
	}
	name := faker.Name()
	u := &schemas.User{
		FirstName: name.FirstName(),
		LastName:  name.LastName(),
		Email:     faker.Internet().SafeEmail(),
		Password:  pwd,
		Role:      "",
	}
	user, err := Create(u)
	if err != nil {
		t.Error(err)
	}
	users, err := GetAll()
	if err != nil {
		t.Error(err)
	}
	if len(users) == 0 {
		t.Error("response users has no data")
	} else {
		var exists bool
		for _, respUser := range users {
			if respUser.Email == user.Email {
				exists = true
			}
		}
		if !exists {
			t.Errorf(
				"response users is missing user %s",
				user.Email,
			)
		}
	}
}

func TestRandomModeratorRole(t *testing.T) {
	role := "moderator"
	user, err := Random(role)
	if err != nil {
		t.Error(err)
	}
	if user.Role != role {
		t.Errorf(
			"response user role %s does not match %s",
			user.Role,
			role,
		)
	}
}

func TestRandomVendorRole(t *testing.T) {
	role := "vendor"
	user, err := Random(role)
	if err != nil {
		t.Error(err)
	}
	if user.Role != role {
		t.Errorf(
			"response user role %s does not match %s",
			user.Role,
			role,
		)
	}
}

func TestRandomAnyRole(t *testing.T) {
	_, err := Random("?")
	if err != nil {
		t.Error(err)
	}
}
