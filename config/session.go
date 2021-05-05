package config

import (
	"fmt"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/session"
)

var ses *session.Store

func OpenSessionStore() *session.Store {
	if ses == nil {
		ses = session.New()
	}
	return ses
}

func Set(c *fiber.Ctx, key string, value string) (err error) {
	_ses := OpenSessionStore()
	store, err := _ses.Get(c)
	defer store.Save()
	if err != nil {
		return
	}
	store.Set(key, value)
	return
}

func Get(c *fiber.Ctx, key string) (value string, err error) {
	_ses := OpenSessionStore()
	store, err := _ses.Get(c)
	if err != nil {
		return
	}
	v := store.Get(key)
	value = fmt.Sprintf("%v", v)
	return
}
