import React from 'react';
import { Snackbar, Alert } from '@mui/material';

const NotificationSnackbar = ({
  open = false,
  onClose,
  message = '',
  severity = 'info',
  autoHideDuration = 6000,
  ...props
}) => {
  return (
    <Snackbar
      open={open}
      autoHideDuration={autoHideDuration}
      onClose={onClose}
      anchorOrigin={{ vertical: 'top', horizontal: 'right' }}
      {...props}
    >
      <Alert
        onClose={onClose}
        severity={severity}
        variant="filled"
        sx={{ width: '100%' }}
      >
        {message}
      </Alert>
    </Snackbar>
  );
};

export default NotificationSnackbar;




