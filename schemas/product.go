package schemas

type Product struct {
	Id               uint64  `json:"id"`
	UserId           uint64  `json:"user_id,omitempty"`
	CompanyId        uint64  `json:"company_id"`
	Name             string  `json:"name"`
	ShortDescription string  `json:"short_description"`
	LongDescription  string  `json:"long_decision,omitempty"`
	ProductDetails   string  `json:"product_details,omitempty"`
	ImagePath        string  `json:"image_path,omitempty"`
	Cost             float64 `json:"cost"`
	Shippable        bool    `json:"shippable"`
	FreeDelivery     bool    `json:"free_delivery"`
	CreatedAt        string  `json:"created_at"`
	UpdatedAt        string  `json:"updated_at"`
	DeletedAt        string  `json:"deleted_at,omitempty"`
}
