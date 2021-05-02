package main

import (
	"log"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/kkamara/go-ecommerce/engine"
	"github.com/kkamara/go-ecommerce/handlers/home"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/user"
)

func Seed() (err error) {
	type modelType func() error
	models := []modelType{product.Seed, user.Seed}
	for _, m := range models {
		err = m()
		if err != nil {
			return
		}
	}
	return
}

func main() {
	app := *fiber.New(fiber.Config{
		Views: engine.GetEngine(),
	})

	app.Use(logger.New())

	err := Seed()
	if err != nil {
		panic(err)
	}

	app.Static("/", "resources")

	app.Get("/", home.GetHomeHandler)

	log.Fatal(app.Listen(":3000"))
}
