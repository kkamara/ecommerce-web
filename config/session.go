package config

import (
	"github.com/gofiber/fiber/v2/middleware/session"
)

var ses *session.Store

func OpenSessionStore() *session.Store {
	if ses == nil {
		ses = session.New()
	}
	return ses
}
