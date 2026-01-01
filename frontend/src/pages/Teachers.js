import React, { useState, useEffect } from 'react';
import {
  Container,
  Typography,
  Box,
  Button,
  Chip,
  Card,
  CardContent,
  Grid,
  TextField,
  Avatar,
  TableCell
} from '@mui/material';
import {
  Add as AddIcon,
  Person as PersonIcon,
  Email as EmailIcon,
  Phone as PhoneIcon,
  Work as WorkIcon
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';
import { useCrud } from '../hooks';
import {
  DataTable,
  FormDialog,
  SearchAndFilter,
  NotificationSnackbar,
  ActionButtons
} from '../components/common';
import {
  FieldRow,
  fieldInputProps,
  dialogPaperProps,
  FormLayout
} from '../components/common/FormFieldRow';

const Teachers = () => {
  const { isSuperAdmin } = useAuth();
  const [departments, setDepartments] = useState([]);
  const [openDialog, setOpenDialog] = useState(false);
  const [editingTeacher, setEditingTeacher] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });
  const [photoFile, setPhotoFile] = useState(null);
  const [photoPreview, setPhotoPreview] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    teacher_code: '',
    department: '',
    qualification: '',
    status: 'active'
  });

  // Use CRUD hook
  const {
    data: teachers,
    loading,
    error,
    searchTerm,
    filters,
    handleSearch,
    handleFilter,
    fetchData
  } = useCrud('/teachers');

  // Fetch departments
  useEffect(() => {
    const fetchDepartments = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/departments');
        if (!response.ok) throw new Error('Failed to fetch departments');
        const data = await response.json();
        setDepartments(data.data || data || []);
      } catch (err) {
        console.error('Error fetching departments:', err);
      }
    };
    fetchDepartments();
  }, []);

  const handleOpenDialog = (teacherData = null) => {
    if (teacherData) {
      setEditingTeacher(teacherData);
      setFormData({
        name: teacherData.name || '',
        email: teacherData.email || '',
        phone: teacherData.phone || '',
        address: teacherData.address || '',
        teacher_code: teacherData.teacher_code || '',
        department: teacherData.department || '',
        qualification: teacherData.qualification || '',
        status: teacherData.status || 'active'
      });
      setPhotoPreview(teacherData.photo ? `http://127.0.0.1:8000/${teacherData.photo}` : null);
    } else {
      setEditingTeacher(null);
      setFormData({
        name: '',
        email: '',
        phone: '',
        address: '',
        teacher_code: '',
        department: '',
        qualification: '',
        status: 'active'
      });
      setPhotoPreview(null);
    }
    setPhotoFile(null);
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingTeacher(null);
    setFormData({
      name: '',
      email: '',
      phone: '',
      address: '',
      teacher_code: '',
      department: '',
      qualification: '',
      status: 'active'
    });
    setPhotoFile(null);
    setPhotoPreview(null);
  };

  const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setPhotoFile(file);
      const reader = new FileReader();
      reader.onloadend = () => {
        setPhotoPreview(reader.result);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async () => {
    try {
      const url = editingTeacher 
        ? `http://127.0.0.1:8000/api/teachers/${editingTeacher.id}`
        : 'http://127.0.0.1:8000/api/teachers';
      
      const formDataToSend = new FormData();
      Object.keys(formData).forEach(key => {
        formDataToSend.append(key, formData[key]);
      });
      
      if (photoFile) {
        formDataToSend.append('photo', photoFile);
      }
      
      if (editingTeacher) {
        formDataToSend.append('_method', 'PUT');
      }
      
      const response = await fetch(url, {
        method: 'POST',
        body: formDataToSend,
      });

      const data = await response.json();

      if (!response.ok) {
        let errorMessage = 'Có lỗi xảy ra khi lưu giảng viên';
        if (data.errors) {
          errorMessage = Object.values(data.errors).flat().join(', ');
        } else if (data.message) {
          errorMessage = data.message;
        }
        throw new Error(errorMessage);
      }

      setSnackbar({
        open: true,
        message: editingTeacher ? 'Giảng viên đã được cập nhật thành công!' : 'Giảng viên đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchData();
    } catch (err) {
      setSnackbar({
        open: true,
        message: err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (id, name) => {
    if (window.confirm(`Bạn có chắc chắn muốn xóa giảng viên "${name}"?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/teachers/${id}`, {
          method: 'DELETE',
        });

        if (!response.ok) throw new Error('Failed to delete');

        setSnackbar({
          open: true,
          message: 'Giảng viên đã được xóa thành công!',
          severity: 'success'
        });

        fetchData();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  // Filter data
  const filteredTeachers = teachers.filter(teacher => {
    const matchesStatus = !filters.status || teacher.status === filters.status;
    return matchesStatus;
  });

  // Filter options
  const filterOptions = [
    {
      key: 'status',
      label: 'Trạng thái',
      options: [
        { value: 'active', label: 'Hoạt động' },
        { value: 'inactive', label: 'Không hoạt động' }
      ]
    }
  ];

  // Table columns
  const columns = [
    { label: '#', field: 'index', align: 'left' },
    { label: 'Giảng viên', field: 'name', align: 'left' },
    { label: 'Mã GV', field: 'teacher_code', align: 'left' },
    { label: 'Khoa', field: 'department', align: 'left' },
    { label: 'Liên hệ', field: 'contact', align: 'left' },
    { label: 'Trạng thái', field: 'status', align: 'left' },
    { label: 'Thao tác', field: 'actions', align: 'center' }
  ];

  // Custom row renderer
  const renderRow = (teacher, index) => (
    <>
      <TableCell>{index + 1}</TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <Avatar 
            src={teacher.photo ? `http://127.0.0.1:8000/${teacher.photo}` : undefined}
            sx={{ mr: 2, bgcolor: 'secondary.main' }}
          >
            <PersonIcon />
          </Avatar>
          <Box>
            <Typography variant="subtitle2" fontWeight="bold">
              {teacher.name}
            </Typography>
            <Typography variant="caption" color="text.secondary">
              ID: #{teacher.id}
            </Typography>
          </Box>
        </Box>
      </TableCell>
      <TableCell>
        <Chip label={teacher.teacher_code} color="secondary" variant="outlined" size="small" />
      </TableCell>
      <TableCell>
        <Box display="flex" alignItems="center">
          <WorkIcon sx={{ fontSize: 16, mr: 0.5, color: 'text.secondary' }} />
          <Typography variant="body2">
            {teacher.department || 'Chưa phân khoa'}
          </Typography>
        </Box>
      </TableCell>
      <TableCell>
        <Box>
          {teacher.email && (
            <Box display="flex" alignItems="center" mb={0.5}>
              <EmailIcon sx={{ fontSize: 16, mr: 0.5, color: 'text.secondary' }} />
              <Typography variant="caption">{teacher.email}</Typography>
            </Box>
          )}
          {teacher.phone && (
            <Box display="flex" alignItems="center">
              <PhoneIcon sx={{ fontSize: 16, mr: 0.5, color: 'text.secondary' }} />
              <Typography variant="caption">{teacher.phone}</Typography>
            </Box>
          )}
        </Box>
      </TableCell>
      <TableCell>
        <Chip
          label={teacher.status === 'active' ? 'Hoạt động' : 'Không hoạt động'}
          color={teacher.status === 'active' ? 'success' : 'default'}
          size="small"
        />
      </TableCell>
      <TableCell align="center">
        {isSuperAdmin() ? (
          <ActionButtons
            onEdit={() => handleOpenDialog(teacher)}
            onDelete={() => handleDelete(teacher.id, teacher.name)}
          />
        ) : (
          <Typography variant="body2" color="text.secondary">
            Không có quyền
          </Typography>
        )}
      </TableCell>
    </>
  );

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      {/* Header */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Box>
          <Typography variant="h4" component="h1" gutterBottom>
            <PersonIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Giảng Viên
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin giảng viên trong hệ thống
          </Typography>
        </Box>
        {isSuperAdmin() && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm giảng viên
          </Button>
        )}
      </Box>

      {/* Search and Filters */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <SearchAndFilter
            searchTerm={searchTerm}
            onSearchChange={handleSearch}
            filters={filters}
            onFilterChange={handleFilter}
            onClearFilters={() => handleFilter({})}
            filterOptions={filterOptions}
            searchPlaceholder="Tìm kiếm giảng viên..."
          />
        </CardContent>
      </Card>

      {/* Data Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách giảng viên ({filteredTeachers.length} giảng viên)
            </Typography>
          </Box>
          
          <DataTable
            columns={columns}
            data={filteredTeachers}
            loading={loading}
            error={error}
            renderRow={renderRow}
            emptyMessage="Không tìm thấy giảng viên nào"
          />
        </CardContent>
      </Card>

      {/* Form Dialog */}
      <FormDialog
        open={openDialog}
        onClose={handleCloseDialog}
        onSubmit={handleSubmit}
        title={editingTeacher ? 'Chỉnh sửa giảng viên' : 'Thêm giảng viên mới'}
        submitText={editingTeacher ? 'Cập nhật' : 'Tạo mới'}
        PaperProps={dialogPaperProps}
      >
        <FormLayout>
          {photoPreview && (
            <Box display="flex" justifyContent="center">
              <Avatar src={photoPreview} sx={{ width: 100, height: 100 }} />
            </Box>
          )}
          <Button variant="outlined" component="label" fullWidth>
            {photoFile ? 'Đổi ảnh' : 'Tải ảnh lên'}
            <input type="file" hidden accept="image/*" onChange={handlePhotoChange} />
          </Button>
          <FieldRow label="Họ và tên" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Mã giảng viên" required>
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.teacher_code}
              onChange={(e) => setFormData({ ...formData, teacher_code: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Khoa">
            <TextField
              fullWidth
              {...fieldInputProps}
              select
              value={formData.department}
              onChange={(e) => setFormData({ ...formData, department: e.target.value })}
            >
              <option value="">Chọn khoa</option>
              {departments.map((dept) => (
                <option key={dept.id} value={dept.name}>
                  {dept.name}
                </option>
              ))}
            </TextField>
          </FieldRow>
          <FieldRow label="Trình độ chuyên môn">
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.qualification}
              onChange={(e) => setFormData({ ...formData, qualification: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Email">
            <TextField
              fullWidth
              {...fieldInputProps}
              type="email"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Số điện thoại">
            <TextField
              fullWidth
              {...fieldInputProps}
              value={formData.phone}
              onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Địa chỉ">
            <TextField
              fullWidth
              {...fieldInputProps}
              multiline
              rows={2}
              value={formData.address}
              onChange={(e) => setFormData({ ...formData, address: e.target.value })}
            />
          </FieldRow>
          <FieldRow label="Trạng thái">
            <TextField
              fullWidth
              {...fieldInputProps}
              select
              value={formData.status}
              onChange={(e) => setFormData({ ...formData, status: e.target.value })}
            >
              <option value="active">Hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </TextField>
          </FieldRow>
        </FormLayout>
      </FormDialog>

      {/* Notification */}
      <NotificationSnackbar
        open={snackbar.open}
        message={snackbar.message}
        severity={snackbar.severity}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
      />
    </Container>
  );
};

export default Teachers;
