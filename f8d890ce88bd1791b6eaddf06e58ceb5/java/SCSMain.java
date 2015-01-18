package SCS;

import java.awt.Dimension;
import java.awt.EventQueue;
import java.awt.Font;
import java.awt.Toolkit;
import java.awt.datatransfer.Clipboard;
import java.awt.datatransfer.StringSelection;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.util.ArrayList;
import java.awt.geom.*;
import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

import javax.swing.Box;
import javax.swing.BoxLayout;
import javax.swing.GroupLayout;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JComponent;
import javax.swing.JDialog;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JPanel;
import javax.swing.JPasswordField;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;

public class SCSMain {

	private JFrame frame;
	private JMenuBar menubar;
	JPasswordField PINField;
	JTextField amountField;
	JTextField accountField;
	JTextField OTPText;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					SCSMain window = new SCSMain();
					window.frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the application.
	 */
	public SCSMain() {
		initialize();
	}

	/**
	 * Initialize the contents of the frame.
	 */
	private void initialize() {
		frame = new JFrame();
		frame.setTitle("PiggyBank GmbH");
		frame.setSize(350, 400);
		frame.setLocationRelativeTo(null);
		frame.setResizable(false);
//                frame.setShape(new RoundRectangle2D.Double(0,0,410,510,5,5));
                frame.setBackground(Color.BLACK);
		try {

			// region Menus
			menubar = new JMenuBar();
			JMenu mainMenuItem = new JMenu("SCS");
			mainMenuItem.setMnemonic(KeyEvent.VK_F);

			JMenuItem eMenuItem = new JMenuItem("Exit");
			eMenuItem.setMnemonic(KeyEvent.VK_E);
			eMenuItem.setToolTipText("Exit application");
			eMenuItem.addActionListener(new ActionListener() {
				@Override
				public void actionPerformed(ActionEvent event) {
					System.exit(0);
				}
			});

			mainMenuItem.add(eMenuItem);
			menubar.add(mainMenuItem);

			frame.setJMenuBar(menubar);

			//mainMenuItem = new JMenu("Help");
			//mainMenuItem.setMnemonic(KeyEvent.VK_H);

			//eMenuItem = new JMenuItem("About");
			//eMenuItem.setMnemonic(KeyEvent.VK_A);
			//eMenuItem.setToolTipText("About Application");
			//eMenuItem.addActionListener(new ActionListener() {
			//	@Override
			//	public void actionPerformed(ActionEvent event) {
			//		AboutDialog ad = new AboutDialog();
			//		ad.pack();
			//		ad.setVisible(true);
			//	}
			//});

			//mainMenuItem.add(eMenuItem);
			//menubar.add(mainMenuItem);
			//frame.setJMenuBar(menubar);
			// endregion

			// region Components
			JComponent panel = new JPanel();
			//panel.setBorder(new EmptyBorder(10, 10, 10, 10));
			GroupLayout layout = new GroupLayout(panel);
			panel.setLayout(layout);
			//layout.setAutoCreateGaps(true);

			ImageIcon icon = new ImageIcon(getClass().getResource("/images/logo.png"));
			JLabel logo = new JLabel(icon);
			logo.setAlignmentX(0.2f);

			JLabel PINLabel = new JLabel("PIN", SwingConstants.CENTER);
                        PINLabel.setMaximumSize(new Dimension(100,30));
			JLabel amountLabel = new JLabel("Amount", SwingConstants.CENTER);
                        amountLabel.setMaximumSize(new Dimension(100,30));
			JLabel accountLabel = new JLabel("Receiver Account",SwingConstants.CENTER);
                        accountLabel.setMaximumSize(new Dimension(100,30));
			JLabel OTPLabel = new JLabel("Transfer Token", SwingConstants.CENTER);
                        OTPLabel.setMaximumSize(new Dimension(100,30));
			JLabel emptyLabel = new JLabel("");
			JLabel logoLabel = new JLabel("PiggyBank GmbH - SCS",SwingConstants.CENTER);
			logoLabel.setFont(new Font("Serif", Font.BOLD, 15));

			PINField = new JPasswordField();
			amountField = new JTextField();
			accountField = new JTextField();
			OTPText = new JTextField("");
			
			PINField.setMaximumSize(new Dimension(200, 30));
			amountField.setMaximumSize(new Dimension(200, 30));
			accountField.setMaximumSize(new Dimension(200, 30));
			OTPText.setMaximumSize(new Dimension(200, 30));
			OTPText.setEditable(false);

			JButton generateButton = new JButton("Generate");
			JButton closeButton = new JButton("Close");
			JButton copyPasteButton = new JButton("Copy OTP");

			GroupLayout.SequentialGroup leftToRight = layout.createSequentialGroup();

			GroupLayout.ParallelGroup columnRight = layout.createParallelGroup(GroupLayout.Alignment.LEADING);
			columnRight.addComponent(logo);
			columnRight.addComponent(PINLabel);
			columnRight.addComponent(amountLabel);
			columnRight.addComponent(accountLabel);
			columnRight.addComponent(OTPLabel);
			leftToRight.addGroup(columnRight);

			GroupLayout.ParallelGroup columnMiddle = layout.createParallelGroup(GroupLayout.Alignment.LEADING);
			columnMiddle.addComponent(logoLabel);
			columnMiddle.addComponent(PINField);
			columnMiddle.addComponent(amountField);
			columnMiddle.addComponent(accountField);
			columnMiddle.addComponent(emptyLabel);
			columnMiddle.addComponent(generateButton);
			columnMiddle.addComponent(OTPText);
			columnMiddle.addComponent(copyPasteButton);
//			columnMiddle.addComponent(closeButton);
			leftToRight.addGroup(columnMiddle);

			GroupLayout.SequentialGroup topToBottom = layout.createSequentialGroup();

			GroupLayout.ParallelGroup row0 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row0.addComponent(logo);
			row0.addComponent(logoLabel);
			topToBottom.addGroup(row0);

                        GroupLayout.ParallelGroup empty1 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
                        empty1.addGap(10);
                        topToBottom.addGroup(empty1);

			GroupLayout.ParallelGroup row1 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row1.addComponent(PINLabel);
			row1.addComponent(PINField);
			topToBottom.addGroup(row1);

			GroupLayout.ParallelGroup row2 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row2.addComponent(amountLabel);
			row2.addComponent(amountField);
			topToBottom.addGroup(row2);

			GroupLayout.ParallelGroup row3 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row3.addComponent(accountLabel);
			row3.addComponent(accountField);
			topToBottom.addGroup(row3);

                        GroupLayout.ParallelGroup empty2 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
                        empty2.addGap(10);
                        topToBottom.addGroup(empty2);

			GroupLayout.ParallelGroup row4 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row4.addComponent(emptyLabel);
			row4.addComponent(generateButton);
			topToBottom.addGroup(row4);

                        GroupLayout.ParallelGroup empty3 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
                        empty3.addGap(10);
                        topToBottom.addGroup(empty3);

			GroupLayout.ParallelGroup row5 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row5.addComponent(OTPLabel);
			row5.addComponent(OTPText);
			topToBottom.addGroup(row5);

                        GroupLayout.ParallelGroup empty4 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
                        empty4.addGap(10);
                        topToBottom.addGroup(empty4);

			GroupLayout.ParallelGroup row6 = layout.createParallelGroup(GroupLayout.Alignment.CENTER);
			row6.addComponent(copyPasteButton);
//			row6.addComponent(closeButton);
			topToBottom.addGroup(row6);

			layout.setHorizontalGroup(leftToRight);
			layout.setVerticalGroup(topToBottom);

			frame.add(panel);

			// endregion

			// region Button Actions

			//closeButton.addActionListener(new ActionListener() {
			//	@Override
			//	public void actionPerformed(ActionEvent event) {
			//		System.exit(0);
			//	}
			//});

			copyPasteButton.addActionListener(new ActionListener() {
				@Override
				public void actionPerformed(ActionEvent event) {
					StringSelection stringSelection = new StringSelection(
							OTPText.getText().trim());
					Clipboard clpbrd = Toolkit.getDefaultToolkit()
							.getSystemClipboard();
					clpbrd.setContents(stringSelection, null);
				}
			});

			generateButton.addActionListener(new ActionListener() {
				@SuppressWarnings("deprecation")
				@Override
				public void actionPerformed(ActionEvent event) {
										
					if (validate(PINField.getText().trim(), amountField.getText().trim(),
							accountField.getText().trim())) {
						OTPText.setText(OTPGenerator.Generate(PINField.getText().trim(), amountField.getText().trim(), accountField.getText().trim()));
					} else {
						OTPText.setText("");
					}

				}
			});

			// endregion

		} catch (Exception e) {

		}

		frame.setVisible(true);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

	}

	private boolean validate(String PIN, String amount, String account) {
		boolean flag = true;
		ArrayList<String> errors = new ArrayList<String>();
		try {

			if (PIN == null || PIN.length() == 0) {
				flag = false;
				errors.add("PIN is required");
			} else {
				if (PIN.length() != 6) {
					flag = false;
					errors.add("PIN must be 6 chars long");
				}
				if (!PIN.matches("[0-9]+")) {
					flag = false;
					errors.add("PIN must contain only numbers");
				}
			}

			if (amount == null || amount.length() == 0) {
				flag = false;
				errors.add("Amount is required");
			} else {
				if (amount.length() > 15) {
					flag = false;
					errors.add("Amount is maximum 15 digits long");
				}
				if (!amount.matches("[0-9.]+")) {
					flag = false;
					errors.add("Amount must contain only numbers");
				}
				try {
					Double.parseDouble(amount);
				} catch (Exception e) {
					flag = false;
					errors.add("Amount is not valid value");
				}
			}

			if (account == null || account.length() == 0) {
				flag = false;
				errors.add("Account is required");
			} else {
				if (account.length() != 10) {
					flag = false;
					errors.add("Account must be 10 chars long");
				}
				if (!account.matches("[a-zA-Z0-9]+")) {
					flag = false;
					errors.add("Account has wrong format");
				}
			}

			if (!flag) {
				ErrorDialog ad = new ErrorDialog(errors);
				ad.pack();
				ad.setVisible(true);
			}

		} catch (Exception e) {
			return false;
		}
		return flag;

	}

}

class AboutDialog extends JDialog {

	private static final long serialVersionUID = 2448531559099983779L;

	public AboutDialog() {

		initialize();
	}

	public final void initialize() {

		setLayout(new BoxLayout(getContentPane(), BoxLayout.Y_AXIS));

		add(Box.createRigidArea(new Dimension(0, 10)));

		ImageIcon icon = new ImageIcon(getClass().getResource(
				"/images/logo.png"));
		JLabel label = new JLabel(icon);
		label.setAlignmentX(0.5f);
		add(label);

		add(Box.createRigidArea(new Dimension(0, 10)));

		JLabel name = new JLabel("PiggyBank GmbH");
		name.setFont(new Font("Serif", Font.BOLD, 20));
		name.setAlignmentX(0.5f);
		add(name);

		add(Box.createRigidArea(new Dimension(0, 30)));

		name = new JLabel("Copyright PiggyBank GmbH 2014");
		name.setFont(new Font("Serif", Font.BOLD, 13));
		name.setAlignmentX(0.5f);
		add(name);

		add(Box.createRigidArea(new Dimension(0, 30)));

		JButton close = new JButton("OK");
		close.addActionListener(new ActionListener() {

			public void actionPerformed(ActionEvent event) {
				dispose();
			}
		});

		close.setAlignmentX(0.5f);
		add(close);

		setModalityType(ModalityType.APPLICATION_MODAL);

		JPanel panel = (JPanel) getContentPane();
		panel.setBorder(new EmptyBorder(10, 10, 10, 10));

		setTitle("About Piggy Bank GmbH");
		setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		setLocationRelativeTo(null);
		setSize(300, 350);
	}
}

class ErrorDialog extends JDialog {

	/**
	 * 
	 */
	private static final long serialVersionUID = 3674874281224492056L;

	public ErrorDialog(ArrayList<String> errors) {

		initialize(errors);
	}

	public final void initialize(ArrayList<String> errors) {

		setLayout(new BoxLayout(getContentPane(), BoxLayout.Y_AXIS));

		add(Box.createRigidArea(new Dimension(0, 10)));

		ImageIcon icon = new ImageIcon(getClass().getResource(
				"/images/error.png"));
		JLabel label = new JLabel(icon);
		label.setAlignmentX(0.5f);
		add(label);

		add(Box.createRigidArea(new Dimension(0, 10)));

		JLabel name = new JLabel("Please correct the following errors");
		name.setFont(new Font("Serif", Font.BOLD, 15));
		name.setAlignmentX(0.5f);
		add(name);

		add(Box.createRigidArea(new Dimension(0, 15)));

		int i = 1;
		for (String error : errors) {
			name = new JLabel(i + ". " + error);
			name.setFont(new Font("Serif", Font.BOLD, 13));
			name.setAlignmentX(0.5f);
			add(name);

			add(Box.createRigidArea(new Dimension(0, 15)));
			i++;
		}

		JButton close = new JButton("OK");
		close.addActionListener(new ActionListener() {

			public void actionPerformed(ActionEvent event) {
				dispose();
			}
		});

		close.setAlignmentX(0.5f);
		add(close);

		setModalityType(ModalityType.APPLICATION_MODAL);

		JPanel panel = (JPanel) getContentPane();
		panel.setBorder(new EmptyBorder(10, 10, 10, 10));

		setTitle("About Piggy Bank GmbH");
		setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		setLocationRelativeTo(null);
		setSize(300, 350);
	}
}
