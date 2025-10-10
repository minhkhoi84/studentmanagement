import React from 'react';
import {
  IconButton,
  Tooltip,
  Menu,
  MenuItem,
  ListItemIcon,
  ListItemText,
} from '@mui/material';
import {
  MoreVert as MoreVertIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Visibility as ViewIcon,
} from '@mui/icons-material';

const ActionButtons = ({
  onEdit,
  onDelete,
  onView,
  editTooltip = 'Chỉnh sửa',
  deleteTooltip = 'Xóa',
  viewTooltip = 'Xem chi tiết',
  showView = false,
  showEdit = true,
  showDelete = true,
  ...props
}) => {
  const [anchorEl, setAnchorEl] = React.useState(null);
  const open = Boolean(anchorEl);

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleAction = (action) => {
    action();
    handleClose();
  };

  return (
    <>
      <Tooltip title="Thao tác">
        <IconButton onClick={handleClick} {...props}>
          <MoreVertIcon />
        </IconButton>
      </Tooltip>
      
      <Menu
        anchorEl={anchorEl}
        open={open}
        onClose={handleClose}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'right',
        }}
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right',
        }}
      >
        {showView && onView && (
          <MenuItem onClick={() => handleAction(onView)}>
            <ListItemIcon>
              <ViewIcon fontSize="small" />
            </ListItemIcon>
            <ListItemText>{viewTooltip}</ListItemText>
          </MenuItem>
        )}
        
        {showEdit && onEdit && (
          <MenuItem onClick={() => handleAction(onEdit)}>
            <ListItemIcon>
              <EditIcon fontSize="small" />
            </ListItemIcon>
            <ListItemText>{editTooltip}</ListItemText>
          </MenuItem>
        )}
        
        {showDelete && onDelete && (
          <MenuItem onClick={() => handleAction(onDelete)} sx={{ color: 'error.main' }}>
            <ListItemIcon>
              <DeleteIcon fontSize="small" color="error" />
            </ListItemIcon>
            <ListItemText>{deleteTooltip}</ListItemText>
          </MenuItem>
        )}
      </Menu>
    </>
  );
};

export default ActionButtons;




