package schemas

type OrderProduct struct {
	Id           uint64 `json:"id"`
	OrderId      uint64 `json:"order_id"`
	ProductId    uint64 `json:"product_id"`
	Quantity     uint8  `json:"amount"`
	Cost         uint64 `json:"cost"`
	Shippable    bool   `json:"shippable"`
	FreeDelivery bool   `json:"free_delivery"`
	CreatedAt    string `json:"created_at"`
	UpdatedAt    string `json:"updated_at"`
}
