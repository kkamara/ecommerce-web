package user

import (
	"errors"
	"fmt"
	"math/rand"

	"github.com/bxcodec/faker/v3"
	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper/password"
	"github.com/kkamara/go-ecommerce/models/helper/strings"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/schemas"
)

func IsAcceptedRole(role string) bool {
	if role == "" {
		return true
	}
	for _, acceptedRole := range schemas.AcceptedUserRoles {
		if role == acceptedRole {
			return true
		}
	}
	return false
}

func Create(newUser *schemas.User) (user *schemas.User, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newUser.CreatedAt = now
	newUser.UpdatedAt = now
	newUser.Slug = strings.Slugify(
		fmt.Sprintf("%s %s", newUser.FirstName, newUser.LastName),
		"-",
	)
	res := db.Create(&newUser)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	user = newUser
	if err = res.Error; err != nil {
		return
	}
	return
}

func GetAll() (users []*schemas.User, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	res := db.Where("deleted_at = ?", "").Find(&users)
	err = res.Error
	return
}

func Random(role string) (user *schemas.User, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	if acceptedRole := IsAcceptedRole(role); !acceptedRole {
		err = fmt.Errorf("role %s is not in the accepted list", role)
		return
	}
	var count int64
	db.Where("role = ?", role).Where("deleted_at = ?", "").Order("RANDOM()").Limit(1).Find(&user).Count(&count)
	if count == 0 {
		var pwd string
		pwd, err = password.HashPassword("secret")
		if err != nil {
			return
		}

		u := &schemas.User{
			FirstName: faker.FirstName(),
			LastName:  faker.LastName(),
			Email:     faker.Email(),
			Password:  pwd,
			Role:      role,
		}
		user, err = Create(u)
		if err != nil {
			return
		}
	}
	return
}

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var pwd string
		pwd, err = password.HashPassword("secret")
		if err != nil {
			return
		}
		firstName, lastName := faker.FirstName(), faker.LastName()
		slug := strings.Slugify(
			fmt.Sprintf("%s %s", firstName, lastName),
			"-",
		)
		var role string
		switch rand.Intn(3) {
		case 1:
			role = "vendor"
		case 2:
			role = "moderator"
		}
		user := &schemas.User{
			Slug:      slug,
			FirstName: firstName,
			LastName:  lastName,
			Email:     faker.Email(),
			Password:  pwd,
			Role:      role,
		}

		_, err = Create(user)
		if err != nil {
			return
		}
	}
	return
}
