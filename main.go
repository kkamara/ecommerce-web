package main

import (
	"fmt"
	"log"
	"os"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/kkamara/go-ecommerce/engine"
	"github.com/kkamara/go-ecommerce/handlers/home"
	"github.com/kkamara/go-ecommerce/models/company"
	"github.com/kkamara/go-ecommerce/models/product"
	"github.com/kkamara/go-ecommerce/models/user"
)

func Seed() (err error) {
	type modelType func() error
	models := []modelType{user.Seed, company.Seed, product.Seed}
	for _, m := range models {
		err = m()
		if err != nil {
			return
		}
	}
	return
}

func main() {
	port := os.Getenv("PORT")
	if port == "" {
		port = "3000"
	}
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

	log.Fatal(app.Listen(fmt.Sprintf(":%s", port)))
}
