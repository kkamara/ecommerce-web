package number

import "math/rand"

func RandFloat(min, max float64) float64 {
	return min + rand.Float64()*(max-min)
}
