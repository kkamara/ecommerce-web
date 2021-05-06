package number

import (
	"math/rand"
)

func RandFloat(min, max int) float64 {
	return float64(min) + rand.Float64()*(float64(max)-float64(min))
}

func RandInt(min, max int) int {
	return int(RandFloat(min, max))
}
