package main

import (
	"log"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/kkamara/laravel-ecommerce/engine"
	"github.com/kkamara/laravel-ecommerce/handlers/home"
)

func main() {
	app := *fiber.New(fiber.Config{
		Views: engine.GetEngine(),
	})

	app.Use(logger.New())

	app.Static("/", "resources")

	app.Get("/", home.GetHomeHandler)

	log.Fatal(app.Listen(":3000"))
}
