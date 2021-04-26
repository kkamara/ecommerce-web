package schemas

type UserPaymentConfig struct {
	Id              uint64 `json:"id"`
	UserId          uint64 `json:"user_id"`
	CardHolderName  string `json:"card_holder_name"`
	CardNumber      uint8  `json:"card_number"`
	ExpriryMonth    uint8  `json:"expiry_month"`
	ExpiryYear      uint8  `json:"expiry_year"`
	MobileNumber    string `json:"mobile_number,omitempty"`
	MobileNumberExt string `json:"mobile_number_ext,omitempty"`
	PhoneNumber     string `json:"phone_number"`
	PhoneNumberExt  string `json:"phone_number_ext,omitempty"`
	BuildingName    string `json:"building_name"`
	StreetAddress1  string `json:"street_address_1"`
	StreetAddress2  string `json:"street_address_2,omitempty"`
	StreetAddress3  string `json:"street_address_3,omitempty"`
	StreetAddress4  string `json:"street_address_4,omitempty"`
	County          string `json:"county,omitempty"`
	City            string `json:"city"`
	Country         string `json:"country"`
	Postcode        string `json:"postcode"`
	CreatedAt       string `json:"created_at"`
	UpdatedAt       string `json:"updated_at"`
	DeletedAt       string `json:"deleted_at,omitempty"`
}
