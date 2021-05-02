package user

import (
	"testing"

	"github.com/bxcodec/faker/v3"
	"github.com/kkamara/go-ecommerce/models/helper"
	"github.com/kkamara/go-ecommerce/schemas"
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
	password, err := helper.HashPassword("secret")
	if err != nil {
		t.Error(err)
	}
	u := &schemas.User{
		FirstName: faker.FirstName(),
		LastName:  faker.LastName(),
		Email:     faker.Email(),
		Password:  password,
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
