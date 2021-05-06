package payment

import (
	"errors"
	"fmt"
	"math/rand"
	"strconv"
	"strings"

	"github.com/kkamara/go-ecommerce/config"
	"github.com/kkamara/go-ecommerce/models/helper/time"
	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func Create(newPayment *schemas.UserPaymentConfig) (company *schemas.UserPaymentConfig, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	now := time.Now()
	newPayment.CreatedAt = now
	newPayment.UpdatedAt = now
	res := db.Create(&newPayment)
	if res.RowsAffected < 1 {
		err = errors.New("error creating resource")
		return
	}
	company = newPayment
	if err = res.Error; err != nil {
		return
	}
	return
}

func GetPayment(userId uint64) (paymentConfig *schemas.UserPaymentConfig, err error) {
	db, err := config.OpenDB()
	if nil != err {
		return
	}
	res := db.Model(&schemas.UserPaymentConfig{}).Joins(
		"left join users on user_payment_configs.user_id = users.id",
	).Where(
		"users.id = ?",
		userId,
	).Limit(1).Find(&paymentConfig)
	err = res.Error
	return
}

func Random() (paymentConfig *schemas.UserPaymentConfig, err error) {
	db, err := config.OpenDB()
	if err != nil {
		return
	}
	var count int64
	db.Where("deleted_at = ?", "").Order("RANDOM()").Limit(1).Find(&paymentConfig).Count(&count)
	if count == 0 {
		var u *schemas.User
		u, err = user.Random("")
		if err != nil {
			return
		}
		var business = faker.Business()
		var expiryYear, expiryMonth int
		expiry := strings.Split(business.CreditCardExpiryDate(), "-")
		expiryYear, err = strconv.Atoi(expiry[0])
		if err != nil {
			return
		}
		expiryMonth, err = strconv.Atoi(expiry[1])
		if err != nil {
			return
		}
		var buildingName string
		if rand.Intn(2) == 1 {
			buildingName = faker.Company().Name()
		}
		creditCartNumber := strings.Join(strings.Split(business.CreditCardNumber(), "-"), "")
		var cc int
		cc, err = strconv.Atoi(creditCartNumber)
		if err != nil {
			return
		}
		address := faker.Address()
		payment := &schemas.UserPaymentConfig{
			UserId:         u.Id,
			CardHolderName: fmt.Sprintf("%s %s", u.FirstName, u.LastName),
			CardNumber:     uint8(cc),
			ExpriryMonth:   uint8(expiryMonth),
			ExpiryYear:     uint8(expiryYear),
			MobileNumber:   faker.PhoneNumber().CellPhone(),
			BuildingName:   buildingName,
			StreetAddress1: address.StreetAddress(),
			City:           address.City(),
			Country:        address.Country(),
			Postcode:       address.Postcode(),
		}
		paymentConfig, err = Create(payment)
		if err != nil {
			return
		}
	}
	return
}

func Seed() (err error) {
	for count := 0; count < 30; count++ {
		var u *schemas.User
		u, err = user.Random("")
		if err != nil {
			return
		}
		var business = faker.Business()
		var expiryYear, expiryMonth int
		expiry := strings.Split(business.CreditCardExpiryDate(), "-")
		expiryYear, err = strconv.Atoi(expiry[0])
		if err != nil {
			return
		}
		expiryMonth, err = strconv.Atoi(expiry[1])
		if err != nil {
			return
		}
		var buildingName string
		if rand.Intn(2) == 1 {
			buildingName = faker.Company().Name()
		}
		creditCartNumber := strings.Join(strings.Split(business.CreditCardNumber(), "-"), "")
		var cc int
		cc, err = strconv.Atoi(creditCartNumber)
		if err != nil {
			return
		}
		address := faker.Address()
		payment := &schemas.UserPaymentConfig{
			UserId:         u.Id,
			CardHolderName: fmt.Sprintf("%s %s", u.FirstName, u.LastName),
			CardNumber:     uint8(cc),
			ExpriryMonth:   uint8(expiryMonth),
			ExpiryYear:     uint8(expiryYear),
			MobileNumber:   faker.PhoneNumber().CellPhone(),
			BuildingName:   buildingName,
			StreetAddress1: address.StreetAddress(),
			City:           address.City(),
			Country:        address.Country(),
			Postcode:       address.Postcode(),
		}

		_, err = Create(payment)
		if err != nil {
			return
		}
	}
	return
}
