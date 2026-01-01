import React from 'react';
import { Grid, Typography, Box } from '@mui/material';

export const fieldInputProps = {
  sx: {
    borderRadius: '12px',
    width: '100%',
    '& .MuiInputBase-root': {
      borderRadius: '12px',
      backgroundColor: 'background.paper',
      boxShadow: '0 2px 15px rgba(15, 23, 42, 0.08)',
      minHeight: 56
    },
    '& .MuiOutlinedInput-notchedOutline': {
      borderColor: 'grey.300'
    },
    '& input': {
      padding: '14px 16px',
      fontSize: '0.95rem'
    },
    '& textarea': {
      padding: '14px 16px'
    }
  }
};

export const dialogPaperProps = {
  sx: {
    borderRadius: 4,
    minWidth: { xs: '280px', sm: '520px' },
    bgcolor: 'background.paper'
  }
};

export const FieldRow = ({ label, required, children }) => (
  <Grid container spacing={2} alignItems="center">
    <Grid item xs={12} sm={3}>
      <Typography variant="body2" fontWeight={600} color="text.primary">
        {label}
        {required && (
          <Typography component="span" color="error" sx={{ ml: 0.5 }}>
            *
          </Typography>
        )}
      </Typography>
    </Grid>
    <Grid item xs={12} sm={9}>
      {children}
    </Grid>
  </Grid>
);

export const FormLayout = ({ children }) => (
  <Box
    sx={{
      mt: 1,
      display: 'flex',
      flexDirection: 'column',
      gap: 3,
      px: { xs: 2, sm: 3 },
      pb: 2
    }}
  >
    {children}
  </Box>
);

