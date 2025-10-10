import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import {
  Container,
  Typography,
  Box,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  CircularProgress,
  Alert,
  Card,
  CardContent,
  Grid,
  TextField,
  Select,
  MenuItem,
  FormControl,
  InputLabel,
  IconButton,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Snackbar
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  Refresh as RefreshIcon,
  Assessment as AssessmentIcon,
  School as SchoolIcon,
  Person as PersonIcon,
  Book as BookIcon,
  Grade as GradeIcon
} from '@mui/icons-material';

const Grades = () => {
  const { isSuperAdmin, hasPermission } = useAuth();
  const [grades, setGrades] = useState([]);
  const [students, setStudents] = useState([]);
  const [courses, setCourses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [openDialog, setOpenDialog] = useState(false);
  const [editingGrade, setEditingGrade] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  const [formData, setFormData] = useState({
    student_id: '',
    course_id: '',
    midterm_score: '',
    final_score: '',
    semester: '',
    academic_year: '',
    notes: ''
  });

  useEffect(() => {
    fetchGrades();
    fetchStudents();
    fetchCourses();
  }, [searchTerm]);

  const fetchGrades = async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (searchTerm) params.append('search', searchTerm);

      const response = await fetch(`http://127.0.0.1:8000/api/grades?${params}`);
      if (!response.ok) throw new Error('Failed to fetch grades');
      const data = await response.json();
      setGrades(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const fetchStudents = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/students');
      if (!response.ok) throw new Error('Failed to fetch students');
      const data = await response.json();
      setStudents(data.data || data || []);
    } catch (err) {
      console.error('Error fetching students:', err);
    }
  };

  const fetchCourses = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/courses');
      if (!response.ok) throw new Error('Failed to fetch courses');
      const data = await response.json();
      setCourses(data.data || data || []);
    } catch (err) {
      console.error('Error fetching courses:', err);
    }
  };

  const handleOpenDialog = (gradeData = null) => {
    if (gradeData) {
      setEditingGrade(gradeData);
      setFormData({
        student_id: gradeData.student_id || '',
        course_id: gradeData.course_id || '',
        midterm_score: gradeData.midterm_score || '',
        final_score: gradeData.final_score || '',
        semester: gradeData.semester || '',
        academic_year: gradeData.academic_year || '',
        notes: gradeData.notes || ''
      });
    } else {
      setEditingGrade(null);
      setFormData({
        student_id: '',
        course_id: '',
        midterm_score: '',
        final_score: '',
        semester: '',
        academic_year: '',
        notes: ''
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingGrade(null);
    setFormData({
      student_id: '',
      course_id: '',
      midterm_score: '',
      final_score: '',
      semester: '',
      academic_year: '',
      notes: ''
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingGrade 
        ? `http://127.0.0.1:8000/api/grades/${editingGrade.id}`
        : 'http://127.0.0.1:8000/api/grades';
      
      const method = editingGrade ? 'PUT' : 'POST';
      
      // Tính điểm tổng kết: Cuối kỳ * 0.6 + Giữa kỳ * 0.4
      const midterm = parseFloat(formData.midterm_score) || 0;
      const final = parseFloat(formData.final_score) || 0;
      const total = (final * 0.6) + (midterm * 0.4);
      
      const submitData = {
        ...formData,
        midterm_score: midterm,
        final_score: final,
        total_score: total.toFixed(2)
      };
      
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(submitData),
      });

      let data;
      try {
        data = await response.json();
      } catch (parseError) {
        throw new Error('Server trả về dữ liệu không hợp lệ. Vui lòng kiểm tra lại thông tin đã nhập.');
      }

      if (!response.ok) {
        // Hiển thị lỗi validation chi tiết
        let errorMessage = 'Có lỗi xảy ra khi lưu điểm';
        if (data.errors) {
          errorMessage = Object.values(data.errors).flat().join(', ');
        } else if (data.message) {
          errorMessage = data.message;
        }
        throw new Error(errorMessage);
      }

      setSnackbar({
        open: true,
        message: editingGrade ? 'Điểm đã được cập nhật thành công!' : 'Điểm đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchGrades();
    } catch (err) {
      setSnackbar({
        open: true,
        message: err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (gradeId, studentName, courseName) => {
    if (window.confirm(`Bạn có chắc muốn xóa điểm của "${studentName}" trong môn "${courseName}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/grades/${gradeId}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to delete grade');
        }

        setSnackbar({
          open: true,
          message: 'Điểm đã được xóa thành công!',
          severity: 'success'
        });

        fetchGrades();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  const getGradeColor = (score) => {
    const numGrade = parseFloat(score);
    if (numGrade >= 8.5) return 'success';
    if (numGrade >= 7.0) return 'info';
    if (numGrade >= 5.5) return 'warning';
    return 'error';
  };

  const getGradeText = (score) => {
    const numGrade = parseFloat(score);
    if (numGrade >= 8.5) return 'Xuất sắc';
    if (numGrade >= 7.0) return 'Khá';
    if (numGrade >= 5.5) return 'Trung bình';
    return 'Yếu';
  };

  const getStudentName = (studentId) => {
    const student = students.find(s => s.id === studentId);
    return student ? student.name : 'Không tìm thấy';
  };

  const getCourseName = (courseId) => {
    const course = courses.find(c => c.id === courseId);
    return course ? course.name : 'Không tìm thấy';
  };

  // Không cần filter ở frontend vì đã search ở backend
  const filteredGrades = grades;

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="400px">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return <Alert severity="error">Error: {error}</Alert>;
  }

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      {/* Header Section */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Box>
          <Typography variant="h4" component="h1" gutterBottom>
            <AssessmentIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Điểm
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý điểm số của sinh viên theo môn học
          </Typography>
        </Box>
        {(isSuperAdmin() || hasPermission('create-grades')) && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm điểm
          </Button>
        )}
      </Box>

      {/* Search Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={8}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên sinh viên, môn học, học kỳ, năm học..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                InputProps={{
                  startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                }}
              />
            </Grid>
            <Grid item xs={12} md={4}>
              <Box display="flex" gap={2}>
                <Button
                  variant="outlined"
                  startIcon={<RefreshIcon />}
                  onClick={fetchGrades}
                >
                  Làm mới
                </Button>
                <Button
                  variant="outlined"
                  onClick={() => setSearchTerm('')}
                >
                  Đặt lại
                </Button>
              </Box>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Grades List */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách điểm ({filteredGrades.length} bản ghi)
            </Typography>
          </Box>
          
          <TableContainer component={Paper} variant="outlined">
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>#</TableCell>
                  <TableCell>Sinh viên</TableCell>
                  <TableCell>Môn học</TableCell>
                  <TableCell>Điểm</TableCell>
                  <TableCell>Học kỳ</TableCell>
                  <TableCell>Năm học</TableCell>
                  <TableCell>Ghi chú</TableCell>
                  <TableCell>Ngày tạo</TableCell>
                  <TableCell align="center">Thao tác</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredGrades.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={9} align="center" sx={{ py: 4 }}>
                      <Box textAlign="center">
                        <AssessmentIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                        <Typography variant="h6" color="text.secondary">
                          Không tìm thấy điểm nào
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                          Hãy thử thay đổi bộ lọc hoặc thêm điểm mới
                        </Typography>
                      </Box>
                    </TableCell>
                  </TableRow>
                ) : (
                  filteredGrades.map((grade, index) => (
                    <TableRow key={grade.id} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <PersonIcon sx={{ mr: 2, color: 'primary.main' }} />
                          <Box>
                            <Typography variant="subtitle2" fontWeight="bold">
                              {getStudentName(grade.student_id)}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              ID: #{grade.student_id}
                            </Typography>
                          </Box>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <BookIcon sx={{ mr: 1, fontSize: 16, color: 'text.secondary' }} />
                          <Typography variant="body2">
                            {getCourseName(grade.course_id)}
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <Chip 
                            label={`${parseFloat(grade.total_score || 0).toFixed(1)} - ${getGradeText(grade.total_score || 0)}`}
                            color={getGradeColor(grade.total_score || 0)}
                            size="small"
                            icon={<GradeIcon />}
                          />
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {grade.semester || 'Chưa cập nhật'}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {grade.academic_year || 'Chưa cập nhật'}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2" color="text.secondary">
                          {grade.notes ? 
                            (grade.notes.length > 30 ? grade.notes.substring(0, 30) + '...' : grade.notes)
                            : 'Không có'
                          }
                        </Typography>
                      </TableCell>
                      <TableCell>
                        {new Date(grade.created_at).toLocaleDateString('vi-VN')}
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" gap={1} justifyContent="center">
                          {(isSuperAdmin() || hasPermission('edit-grades')) && (
                            <IconButton
                              size="small"
                              color="primary"
                              onClick={() => handleOpenDialog(grade)}
                              title="Chỉnh sửa"
                            >
                              <EditIcon />
                            </IconButton>
                          )}
                          {(isSuperAdmin() || hasPermission('delete-grades')) && (
                            <IconButton
                              size="small"
                              color="error"
                              onClick={() => handleDelete(grade.id, getStudentName(grade.student_id), getCourseName(grade.course_id))}
                              title="Xóa điểm"
                            >
                              <DeleteIcon />
                            </IconButton>
                          )}
                        </Box>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </TableContainer>
        </CardContent>
      </Card>

      {/* Add/Edit Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
        <DialogTitle>
          {editingGrade ? 'Chỉnh sửa điểm' : 'Thêm điểm mới'}
        </DialogTitle>
        <form onSubmit={handleSubmit}>
          <DialogContent>
            <Grid container spacing={2} sx={{ mt: 1 }}>
              <Grid item xs={12} sm={6}>
                <FormControl fullWidth>
                  <InputLabel>Sinh viên</InputLabel>
                  <Select
                    value={formData.student_id}
                    label="Sinh viên"
                    onChange={(e) => setFormData({ ...formData, student_id: e.target.value })}
                    required
                  >
                    <MenuItem value="">Chọn sinh viên</MenuItem>
                    {students.map((student) => (
                      <MenuItem key={student.id} value={student.id}>
                        {student.name} - {student.student_code}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} sm={6}>
                <FormControl fullWidth>
                  <InputLabel>Môn học</InputLabel>
                  <Select
                    value={formData.course_id}
                    label="Môn học"
                    onChange={(e) => setFormData({ ...formData, course_id: e.target.value })}
                    required
                  >
                    <MenuItem value="">Chọn môn học</MenuItem>
                    {courses.map((course) => (
                      <MenuItem key={course.id} value={course.id}>
                        {course.name} - {course.code}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Điểm giữa kỳ *"
                  type="number"
                  step="0.1"
                  min="0"
                  max="10"
                  value={formData.midterm_score}
                  onChange={(e) => setFormData({ ...formData, midterm_score: e.target.value })}
                  required
                  helperText="Điểm giữa kỳ (0-10)"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Điểm cuối kỳ *"
                  type="number"
                  step="0.1"
                  min="0"
                  max="10"
                  value={formData.final_score}
                  onChange={(e) => setFormData({ ...formData, final_score: e.target.value })}
                  required
                  helperText="Điểm cuối kỳ (0-10)"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Học kỳ"
                  value={formData.semester}
                  onChange={(e) => setFormData({ ...formData, semester: e.target.value })}
                  required
                  placeholder="VD: 1, 2, 3"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Năm học"
                  value={formData.academic_year}
                  onChange={(e) => setFormData({ ...formData, academic_year: e.target.value })}
                  required
                  placeholder="VD: 2024-2025"
                  inputProps={{ maxLength: 10 }}
                  helperText="Tối đa 10 ký tự"
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Ghi chú"
                  multiline
                  rows={3}
                  value={formData.notes}
                  onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                />
              </Grid>
            </Grid>
          </DialogContent>
          <DialogActions>
            <Button onClick={handleCloseDialog}>Hủy</Button>
            <Button type="submit" variant="contained">
              {editingGrade ? 'Cập nhật' : 'Tạo điểm'}
            </Button>
          </DialogActions>
        </form>
      </Dialog>

      {/* Snackbar for notifications */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={6000}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
      >
        <Alert 
          onClose={() => setSnackbar({ ...snackbar, open: false })} 
          severity={snackbar.severity}
        >
          {snackbar.message}
        </Alert>
      </Snackbar>
    </Container>
  );
};

export default Grades;
