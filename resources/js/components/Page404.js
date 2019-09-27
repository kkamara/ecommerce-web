import React from "react";
import { GIPHY_API_KEY } from "../constants";
import Loader from "./Loader";

class Page404 extends React.Component {
    state = {
        giphyImageUrl: null
    };

    async componentDidMount() {
        let giphyImageUrl = await this.getGiphyImage();

        this.setGiphyImage(giphyImageUrl);
    }

    setGiphyImage(giphyImageUrl) {
        this.setState({ giphyImageUrl });
    }

    getGiphyImage = async () => {
        let imageUrl = await fetch(
            `https://api.giphy.com/v1/gifs/random?api_key=${GIPHY_API_KEY}`
        )
            .then(res => res.json())
            .then(res => {
                return res.data.image_original_url;
            })
            .catch(err => {
                console.log("giphy image request", err);
                return false;
            });

        return imageUrl;
    };

    render() {
        const { container } = styles;
        const { giphyImageUrl } = this.state;

        if (giphyImageUrl === null) {
            return <Loader />;
        } else {
            return (
                <div className="container" style={container}>
                    <div className="text-center">
                        <h2>Oops, page not found</h2>

                        {giphyImageUrl ? (
                            <img src={giphyImageUrl} alt="404_image" />
                        ) : (
                            <div />
                        )}
                    </div>
                </div>
            );
        }
    }
}

const styles = {
    container: {
        marginTop: 100
    }
};

export default Page404;
