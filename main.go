package main

import (
	"log"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/kkamara/laravel-ecommerce/engine"
)

func main() {
	app := *fiber.New(fiber.Config{
		Views: engine.GetEngine(),
	})

	app.Use(logger.New())


	app.Get("/", func(c *fiber.Ctx) error {
		return c.Render("index", fiber.Map{
			"Title": "Hello, World!",
		}, "layouts/master")
	})

	log.Fatal(app.Listen(":3000"))

	app.Listen(":3000")
}
