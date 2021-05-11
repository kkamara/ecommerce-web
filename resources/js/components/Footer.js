import React from "react";
import { APP_NAME } from "../constants";

const Footer = props => (
    <footer className="footer" style={styles.footer}>
        <div className="container text-center">
            <span className="text-muted">{APP_NAME} &copy; 2018</span>
        </div>
    </footer>
);

const styles = {
    footer: {
        marginTop: 30
    }
};

export default Footer;
