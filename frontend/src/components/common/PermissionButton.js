import React from 'react';
import { Button, IconButton } from '@mui/material';
import { useAuth } from '../../contexts/AuthContext';

/**
 * Button component với kiểm tra quyền
 * Chỉ hiển thị button nếu user có quyền
 */
export const PermissionButton = ({ 
  permission, 
  children, 
  variant = 'contained',
  ...props 
}) => {
  const { isSuperAdmin, hasPermission } = useAuth();

  if (!isSuperAdmin() && !hasPermission(permission)) {
    return null;
  }

  return (
    <Button variant={variant} {...props}>
      {children}
    </Button>
  );
};

/**
 * IconButton component với kiểm tra quyền
 * Chỉ hiển thị button nếu user có quyền
 */
export const PermissionIconButton = ({ 
  permission, 
  children, 
  ...props 
}) => {
  const { isSuperAdmin, hasPermission } = useAuth();

  if (!isSuperAdmin() && !hasPermission(permission)) {
    return null;
  }

  return (
    <IconButton {...props}>
      {children}
    </IconButton>
  );
};

export default { PermissionButton, PermissionIconButton };

