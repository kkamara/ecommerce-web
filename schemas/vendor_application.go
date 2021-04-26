package schemas

type VendorApplication struct {
	Id                  uint64 `json:"id"`
	UserId              uint64 `json:"user_id"`
	UserAddressId       uint64 `json:"user_address_id,omitempty"`
	ProposedCompanyName string `json:"proposed_company_name,omitempty"`
	Accepted            bool   `json:"accepted,omitempty"`
	DecidedBy           uint64 `json:"decided_by,omitempty"`
	DecisionReason      string `json:"decision_reason,omitempty"`
	CreatedAt           string `json:"created_at"`
	UpdatedAt           string `json:"updated_at"`
	DeletedAt           string `json:"deleted_at,omitempty"`
}
