package user

import (
	"errors"
	"fmt"
	"strings"
	"time"

	"github.com/bxcodec/faker/v3"
	"github.com/kkamara/laravel-ecommerce/config"
	"github.com/kkamara/laravel-ecommerce/models/helper"
	"github.com/kkamara/laravel-ecommerce/schemas"
)

func IsAcceptedRole(role string) bool {
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
		panic(err)
	}
	const createdFormat = "2006-01-02 15:04:05"
	newUser.CreatedAt = time.Now().Format(createdFormat)
	newUser.UpdatedAt = time.Now().Format(createdFormat)
	newUser.Slug = strings.Join(
		[]string{strings.ToLower(newUser.FirstName), strings.ToLower(newUser.LastName)},
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
		panic(err)
	}
	res := db.Find(&users)
	err = res.Error
	return
}

func Random(role string) (user *schemas.User, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	q := db
	if len(role) != 0 {
		q = q.Where("role = ?", role)
	} else {
		if acceptedRole := IsAcceptedRole(role); !acceptedRole {
			err = fmt.Errorf("role %s is not in the accepted list", role)
			return
		}
	}
	var count int64
	q.Order("RANDOM()").Limit(1).Find(&user).Count(&count)
	if count == 0 {
		var password string
		password, err = helper.HashPassword("secret")
		if err != nil {
			return
		}

		u := &schemas.User{
			FirstName: faker.FirstName(),
			LastName:  faker.LastName(),
			Email:     faker.Email(),
			Password:  password,
			Role:      role,
		}
		user, err = Create(u)
		if err != nil {
			return
		}
	}
	return
}
