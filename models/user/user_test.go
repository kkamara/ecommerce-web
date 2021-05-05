package user

import (
	"testing"

	"github.com/kkamara/go-ecommerce/models/helper/password"
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
