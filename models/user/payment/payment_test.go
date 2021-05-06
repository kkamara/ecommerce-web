package payment

import (
	"math/rand"
	"strconv"
	"strings"
	"testing"

	"github.com/kkamara/go-ecommerce/models/user"
	"github.com/kkamara/go-ecommerce/schemas"
	"syreclabs.com/go/faker"
)

func TestGetPayment(t *testing.T) {
	cardHolderName := "Bilbo Swaggins"
	user, err := user.Random("?")
	if err != nil {
		t.Error(err)
	}
	var business = faker.Business()
	expiry := strings.Split(business.CreditCardExpiryDate(), "-")
	expiryYear, err := strconv.Atoi(expiry[0])
	if err != nil {
		return
	}
	expiryMonth, err := strconv.Atoi(expiry[1])
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
		UserId:         user.Id,
		CardHolderName: cardHolderName,
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
		t.Error(err)
	}
	paymentConfig, err := GetPayment(user.Id)
	if err != nil {
		t.Error(err)
	}
	if paymentConfig.UserId != user.Id {
		t.Errorf(
			"User id %d does not match %d",
			paymentConfig.UserId,
			user.Id,
		)
	}
	if paymentConfig.CardHolderName != cardHolderName {
		t.Errorf(
			"Card holder name %s does not match %s",
			paymentConfig.CardHolderName,
			cardHolderName,
		)
	}
}
