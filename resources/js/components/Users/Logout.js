import React, { Component, Fragment } from "react";
import { connect } from "react-redux";
import { withRouter, Redirect } from "react-router-dom";

import { logoutUser } from "../../requests/user";

class Logout extends Component {
    componentDidMount() {
        const { fetched, isLoaded, user } = this.props.current_user;
        const { history } = this.props;

        if (typeof user !== "undefined") {
            history.push("/404");
        } else {
            this.props.dispatch({
                type: "FETCH_LOGOUT_USER",
                payload: logoutUser
            });

            setTimeout(() => history.push("/"), 1000);
        }
    }

    render() {
        return <div />;
    }
}

const mapStateToProps = state => ({
    user: state.user.user
});
export default connect(
    mapStateToProps,
    null
)(withRouter(Logout));
