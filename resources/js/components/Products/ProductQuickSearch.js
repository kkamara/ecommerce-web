import React, { PureComponent } from "react";
import { Redirect } from "react-router-dom";

const initialState = {
    query: ""
};

class ProductQuickSearch extends PureComponent {
    state = { ...initialState };

    handleChange(e) {
        const { name, value } = e.target;
        this.setState({ [name]: value });
    }

    onSubmit(e) {
        e.preventDefault();

        const { query } = this.state;

        this.props.history.push(`/?query=${query}`);
    }

    render() {
        const { query } = this.state;

        return (
            <form
                className="form-inline my-2 my-lg-0"
                onSubmit={e => this.onSubmit(e)}
            >
                <input
                    name="query"
                    value={query}
                    onChange={e => this.handleChange(e)}
                    className="form-control mr-sm-2"
                    type="search"
                    placeholder="Find Your Product"
                    aria-label="Search"
                />
                <button
                    className="btn btn-outline-success my-2 my-sm-0"
                    type="submit"
                >
                    Search
                </button>
            </form>
        );
    }
}

export default ProductQuickSearch;
