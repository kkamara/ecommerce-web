import React, { Component } from "react";
import { connect } from "react-redux";
import { withRouter } from "react-router-dom";

import { userActions } from "../../redux/actions/index";

class Logout extends Component {
    componentDidUpdate() {
        const { fetched, isLoaded, data: userData } = this.props.current_user;

        if (fetched && isLoaded && userData) {
            this.props.logoutUser();
        }
    }

    render() {
        return <div />;
    }
}

const mapStateToProps = state => ({
    current_user: state.current_user,
});
const mapDispatchToProps = dispatch => ({
    logoutUser: () => dispatch(userActions.logoutUser())
});
export default connect(
    mapStateToProps,
    mapDispatchToProps
)(withRouter(Logout));
