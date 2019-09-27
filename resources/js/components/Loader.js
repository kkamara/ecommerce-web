import React from "react";
import FadeLoader from "react-spinners/ClipLoader";

const Loader = () => (
    <div>
        <div className="sweet-loading text-center">
            <FadeLoader
                // css={override}
                sizeUnit={"px"}
                size={150}
                color={"#123abc"}
                loading={true}
            />
        </div>
    </div>
);

export default Loader;
