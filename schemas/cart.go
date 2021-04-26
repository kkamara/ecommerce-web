package schemas

type CartSchema struct {
	Id        uint64 `json:id`
	UserId    uint64 `json:user_id`
	ProductId uint64 `json:product_id`
}
